<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\AdminMessage;
use App\Models\AdminMessageReply;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class UserProfileTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Créer les rôles nécessaires
        \Spatie\Permission\Models\Role::create(['name' => 'user']);
        \Spatie\Permission\Models\Role::create(['name' => 'admin']);
        
        // Créer un utilisateur de test avec toutes les données
        $this->user = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'username' => 'johndoe',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
            'biography' => 'Je suis un utilisateur de test avec une biographie complète.',
            'is_newsletter_subscribed' => true,
            'newsletter_subscribed_at' => now()->subDays(30),
        ]);

        // Assigner le rôle user
        $this->user->assignRole('user');

        // Créer des données associées à l'utilisateur
        $this->createUserData();
    }

    protected function createUserData()
    {
        // Créer un utilisateur admin pour les réponses
        $admin = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'username' => 'admin',
            'password' => Hash::make('password123'),
            'email_verified_at' => now(),
        ]);
        $admin->assignRole('admin');

        // Créer des messages admin
        $message1 = AdminMessage::create([
            'user_id' => $this->user->id,
            'subject' => 'Problème avec ma commande',
            'message' => 'J\'ai un problème avec ma dernière commande.',
            'status' => 'resolved',
            'resolved_at' => now()->subDays(5),
        ]);

        $message2 = AdminMessage::create([
            'user_id' => $this->user->id,
            'subject' => 'Question sur les produits',
            'message' => 'Avez-vous des produits bio ?',
            'status' => 'pending',
        ]);

        // Créer des réponses admin
        AdminMessageReply::create([
            'admin_message_id' => $message1->id,
            'user_id' => $admin->id,
            'message' => 'Nous avons traité votre problème.',
        ]);

        AdminMessageReply::create([
            'admin_message_id' => $message2->id,
            'user_id' => $admin->id,
            'message' => 'Oui, nous avons une gamme complète de produits bio.',
        ]);
    }

    /** @test */
    public function user_can_view_profile()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('profile.show'));

        $response->assertStatus(200);
        $response->assertSee($this->user->name);
        $response->assertSee($this->user->email);
        $response->assertSee($this->user->biography);
        $response->assertSee('Vous êtes abonné(e) à notre newsletter');
    }

    /** @test */
    public function user_can_update_profile()
    {
        $updateData = [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'biography' => 'Nouvelle biographie mise à jour.',
        ];

        $response = $this->actingAs($this->user)
                         ->put(route('profile.update'), $updateData);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertEquals('Jane Doe', $this->user->name);
        $this->assertEquals('jane@example.com', $this->user->email);
        $this->assertEquals('Nouvelle biographie mise à jour.', $this->user->biography);
    }

    /** @test */
    public function user_can_upload_profile_photo()
    {
        Storage::fake('public');

        $file = UploadedFile::fake()->image('profile.jpg', 200, 200);

        $response = $this->actingAs($this->user)
                         ->post(route('profile.photo.upload'), [
                             'photo' => $file
                         ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');

        $this->user->refresh();
        $this->assertNotNull($this->user->profile_photo_path);
        
        Storage::disk('public')->assertExists($this->user->profile_photo_path);
    }

    /** @test */
    public function user_can_toggle_newsletter_subscription()
    {
        // Test désabonnement (utilisateur actuellement abonné)
        $this->assertTrue($this->user->is_newsletter_subscribed);

        $response = $this->actingAs($this->user)
                         ->post(route('profile.newsletter.toggle'));

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHas('success');
        $response->assertSessionHasNoErrors();

        $this->user->refresh();
        $this->assertFalse($this->user->is_newsletter_subscribed);
        $this->assertNotNull($this->user->newsletter_unsubscribed_at);

        // Test réabonnement
        $response = $this->actingAs($this->user)
                         ->post(route('profile.newsletter.toggle'));

        $response->assertRedirect(route('profile.show'));
        $this->user->refresh();
        $this->assertTrue($this->user->is_newsletter_subscribed);
        $this->assertNull($this->user->newsletter_unsubscribed_at);
    }

    /** @test */
    public function user_can_view_messages()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('user.messages.index'));

        $response->assertStatus(200);
        $response->assertSee('Problème avec ma commande');
        $response->assertSee('Question sur les produits');
    }

    /** @test */
    public function user_can_view_message_detail()
    {
        $message = $this->user->adminMessages->first();

        $response = $this->actingAs($this->user)
                         ->get(route('user.messages.show', $message));

        $response->assertStatus(200);
        $response->assertSee($message->subject);
        $response->assertSee($message->message);
        $response->assertSee('Nous avons traité votre problème');
    }

    /** @test */
    public function user_can_download_data_export()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('profile.export-data'));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/zip');
        $response->assertHeader('content-disposition', 'attachment; filename="mes-donnees-' . $this->user->id . '.zip"');

        // Vérifier que le contenu ZIP n'est pas vide
        $this->assertGreaterThan(0, strlen($response->content()));
    }

    /** @test */
    public function user_can_delete_account_with_correct_password()
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('profile.account.delete'), [
                             'password' => 'password123',
                             'confirmation' => '1'
                         ]);

        $response->assertRedirect(route('welcome'));
        $response->assertSessionHas('success');

        // Vérifier que l'utilisateur est supprimé (soft delete)
        $this->assertSoftDeleted('users', [
            'id' => $this->user->id
        ]);

        // Vérifier que l'utilisateur est déconnecté
        $this->assertGuest();
    }

    /** @test */
    public function user_cannot_delete_account_with_wrong_password()
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('profile.account.delete'), [
                             'password' => 'wrongpassword',
                             'confirmation' => '1'
                         ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHasErrors(['password']);

        // Vérifier que l'utilisateur n'est pas supprimé
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function user_cannot_delete_account_without_confirmation()
    {
        $response = $this->actingAs($this->user)
                         ->delete(route('profile.account.delete'), [
                             'password' => 'password123'
                             // Pas de confirmation
                         ]);

        $response->assertRedirect(route('profile.show'));
        $response->assertSessionHasErrors(['confirmation']);

        // Vérifier que l'utilisateur n'est pas supprimé
        $this->assertDatabaseHas('users', [
            'id' => $this->user->id,
            'deleted_at' => null
        ]);
    }

    /** @test */
    public function user_cannot_access_other_user_messages()
    {
        // Créer un autre utilisateur
        $otherUser = User::factory()->create();
        
        // Créer un message pour l'autre utilisateur
        $otherMessage = AdminMessage::create([
            'user_id' => $otherUser->id,
            'subject' => 'Message privé',
            'message' => 'Ce message ne doit pas être accessible.',
            'status' => 'pending',
        ]);

        // Essayer d'accéder au message de l'autre utilisateur
        $response = $this->actingAs($this->user)
                         ->get(route('user.messages.show', $otherMessage));

        $response->assertStatus(403);
    }

    /** @test */
    public function data_export_contains_all_user_data()
    {
        $response = $this->actingAs($this->user)
                         ->get(route('profile.export-data'));

        $response->assertStatus(200);

        // Créer un fichier temporaire pour tester le contenu
        $tempFile = tempnam(sys_get_temp_dir(), 'test_export_');
        file_put_contents($tempFile, $response->content());

        $zip = new \ZipArchive();
        $result = $zip->open($tempFile);
        
        $this->assertTrue($result === true);

        // Vérifier que tous les fichiers attendus sont présents
        $expectedFiles = [
            'profil.json',
            'messages-admin.json',
            'reponses-admin.json'
        ];

        foreach ($expectedFiles as $file) {
            $this->assertNotFalse($zip->locateName($file), "Le fichier {$file} devrait être présent dans l'export");
        }

        // Vérifier le contenu du profil
        $profilContent = $zip->getFromName('profil.json');
        $profilData = json_decode($profilContent, true);
        
        $this->assertEquals($this->user->name, $profilData['name']);
        $this->assertEquals($this->user->email, $profilData['email']);
        $this->assertEquals($this->user->biography, $profilData['biography']);

        // Vérifier le contenu des messages
        $messagesContent = $zip->getFromName('messages-admin.json');
        $messagesData = json_decode($messagesContent, true);
        
        $this->assertCount(2, $messagesData);
        $this->assertEquals('Problème avec ma commande', $messagesData[0]['subject']);

        $zip->close();
        unlink($tempFile);
    }

    /** @test */
    public function deleted_user_cannot_login()
    {
        // Supprimer le compte
        $this->actingAs($this->user)
             ->delete(route('profile.account.delete'), [
                 'password' => 'password123',
                 'confirmation' => '1'
             ]);

        // Essayer de se connecter
        $response = $this->post(route('login'), [
            'email' => $this->user->email,
            'password' => 'password123'
        ]);

        $response->assertSessionHasErrors();
        $this->assertGuest();
    }
}
