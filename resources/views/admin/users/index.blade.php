@extends('layouts.admin')

@section('title', __('users.title'))
@section('page-title', __('users.page_title'))

@section('content')
<div class="container mx-auto px-4 py-8">
    <!-- Messages de notification -->
    @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-center">
            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div>
                <h4 class="text-green-800 font-medium">{{ __('users.success') }}</h4>
                <p class="text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="mb-6 bg-red-50 border border-red-200 rounded-lg p-4 flex items-center">
            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16c-.77.833.192 2.5 1.732 2.5z"/>
            </svg>
            <div>
                <h4 class="text-red-800 font-medium">{{ __('users.error') }}</h4>
                <p class="text-red-700">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Entête avec style moderne -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-600 to-purple-600 rounded-xl p-6 text-white shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-3xl font-bold flex items-center">
                        <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                        </svg>
                        {{ __('users.manage_all_users') }}
                    </h1>
                    <p class="mt-2 text-blue-100">
                        {{ __('users.advanced_interface') }}
                    </p>
                </div>
                <div class="text-right">
                    <div class="text-2xl font-bold">{{ $stats['total'] }}</div>
                    <div class="text-blue-100">{{ __('users.total_users') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-8">
        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-green-600">{{ $stats['users'] }}</div>
                    <div class="text-sm text-green-700">{{ __('users.stats.users') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24
                    ">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-blue-600">{{ $stats['admins'] }}</div>
                    <div class="text-sm text-blue-700">{{ __('users.stats.administrators') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-red-600">{{ $stats['deleted'] }}</div>
                    <div class="text-sm text-red-700">{{ __('users.stats.deleted') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-orange-50 border border-orange-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-orange-600">{{ $stats['new_users'] }}</div>
                    <div class="text-sm text-orange-700">{{ __('users.stats.new_30_days') }}</div>
                </div>
            </div>
        </div>

        <div class="bg-purple-50 border border-purple-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <svg class="h-8 w-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <div class="ml-4">
                    <div class="text-2xl font-bold text-purple-600">{{ $stats['active_users'] }}</div>
                    <div class="text-sm text-purple-700">{{ __('users.stats.active_7_days') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Formulaire de recherche et filtres avancés -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200 mb-8">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-semibold text-gray-800 flex items-center">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                {{ __('users.search.title') }}
            </h2>
        </div>
        
        <form method="GET" action="{{ route('admin.users.index') }}" class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6">
                <!-- Recherche générale -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('users.search.general_search') }}
                    </label>
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="{{ __('users.search.placeholder') }}"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                </div>

                <!-- Filtre par rôle -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                        </svg>
                        {{ __('users.search.filter_by_role') }}
                    </label>
                    <select 
                        name="role"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="">{{ __('users.search.all_roles') }}</option>
                        <option value="Admin" {{ request('role') === 'Admin' ? 'selected' : '' }}>👑 Admin</option>
                        <option value="User" {{ request('role') === 'User' ? 'selected' : '' }}>👤 User</option>
                    </select>
                </div>

                <!-- Filtre par statut de suppression -->
                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        {{ __('users.search.account_status') }}
                    </label>
                    <select 
                        name="show_deleted"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="active" {{ ($showDeleted ?? 'active') === 'active' ? 'selected' : '' }}>{{ __('users.search.active_only') }}</option>
                        <option value="deleted" {{ ($showDeleted ?? 'active') === 'deleted' ? 'selected' : '' }}>{{ __('users.search.deleted_only') }}</option>
                        <option value="all" {{ ($showDeleted ?? 'active') === 'all' ? 'selected' : '' }}>{{ __('users.search.all_accounts') }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16V4m0 0L3 8m4-4l4 4m6 0v12m0 0l4-4m-4 4l-4-4"/>
                        </svg>
                        {{ __('users.search.sort_by') }}
                    </label>
                    <select 
                        name="sort"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="created_at" {{ $sortBy === 'created_at' ? 'selected' : '' }}>{{ __('users.sort_options.created_at') }}</option>
                        <option value="name" {{ $sortBy === 'name' ? 'selected' : '' }}>{{ __('users.sort_options.name') }}</option>
                        <option value="username" {{ $sortBy === 'username' ? 'selected' : '' }}>{{ __('users.sort_options.username') }}</option>
                        <option value="email" {{ $sortBy === 'email' ? 'selected' : '' }}>{{ __('users.sort_options.email') }}</option>
                        <option value="role" {{ $sortBy === 'role' ? 'selected' : '' }}>{{ __('users.sort_options.role') }}</option>
                        <option value="updated_at" {{ $sortBy === 'updated_at' ? 'selected' : '' }}>{{ __('users.sort_options.updated_at') }}</option>
                        <option value="deleted_at" {{ $sortBy === 'deleted_at' ? 'selected' : '' }}>{{ __('users.sort_options.deleted_at') }}</option>
                    </select>
                </div>

                <div class="space-y-2">
                    <label class="flex items-center text-sm font-medium text-gray-700">
                        <svg class="inline w-4 h-4 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        {{ __('users.search.order') }}
                    </label>
                    <select 
                        name="order"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white transition-colors">
                        <option value="desc" {{ $sortOrder === 'desc' ? 'selected' : '' }}>{{ __('users.order_options.desc') }}</option>
                        <option value="asc" {{ $sortOrder === 'asc' ? 'selected' : '' }}>{{ __('users.order_options.asc') }}</option>
                    </select>
                </div>
            </div>

            <div class="flex justify-end space-x-3 mt-6">
                <a href="{{ route('admin.users.index') }}" 
                   class="px-6 py-3 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    {{ __('users.search.reset') }}
                </a>
                <button type="submit" 
                        class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                    {{ __('users.search.apply_filters') }}
                </button>
            </div>
        </form>
    </div>
    <!-- Liste des utilisateurs -->
    <div class="bg-white rounded-xl shadow-lg border border-gray-200">
        @if($users->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.user') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.email') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.role') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.status') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.newsletter') }}
                            </th>
                            <th class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.registration') }}
                            </th>
                            <th class="px-6 py-4 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                {{ __('users.table.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr class="hover:bg-gray-50 {{ $user->trashed() ? 'bg-red-50' : '' }}">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center text-white font-medium text-sm">
                                            {{ substr($user->name ?: $user->username, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name ?: 'N/A' }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->username }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $roleColors = [
                                            'Admin' => 'bg-red-100 text-red-800',
                                            'User' => 'bg-green-100 text-green-800',
                                        ];
                                    @endphp
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? 'bg-gray-100 text-gray-800' }} {{ $user->trashed() ? 'opacity-75' : '' }}">
                                        @if($user->role === 'Admin') 👑 @else 👤 @endif {{ $user->role }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($user->trashed())
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            {{ __('users.status.deleted') }}
                                        </span>
                                        @if($user->deleted_at)
                                            <div class="text-xs text-gray-500 mt-1">{{ $user->deleted_at->format('d/m/Y H:i') }}</div>
                                        @endif
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            {{ __('users.status.active') }}
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $user->newsletter_subscribed ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }} {{ $user->trashed() ? 'opacity-75' : '' }}">
                                        @if($user->newsletter_subscribed) {{ __('users.status.subscribed') }} @else {{ __('users.status.not_subscribed') }} @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $user->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                    <div class="flex items-center justify-center space-x-2">
                                        @if($user->trashed())
                                            <!-- Bouton Restaurer pour les comptes supprimés -->
                                            <form method="POST" 
                                                  action="{{ route('admin.users.restore', $user->id) }}" 
                                                  class="inline" onsubmit="return confirm('Voulez-vous vraiment restaurer cet utilisateur ?')">
                                                @csrf
                                                <button type="submit" 
                                                        class="text-green-600 hover:text-green-900 transition-colors bg-green-50 hover:bg-green-100 px-3 py-2 rounded-lg border border-green-200" 
                                                        title="Restaurer l'utilisateur">
                                                    <svg class="w-5 h-5 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0V9a8 8 0 1115.356 2M15 13l-3-3-3 3m3-3v9"/>
                                                    </svg>
                                                    Restaurer
                                                </button>
                                            </form>
                                        @else
                                            <!-- Actions pour les comptes actifs -->
                                            <!-- Bouton Voir -->
                                            <a href="{{ route('admin.users.show', $user) }}" 
                                               class="text-blue-600 hover:text-blue-900 transition-colors"
                                               title="{{ __('users.actions.view_details') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                            </a>

                                            <!-- Bouton Modifier -->
                                            <a href="{{ route('admin.users.edit', $user) }}" 
                                               class="text-yellow-600 hover:text-yellow-900 transition-colors"
                                               title="{{ __('users.actions.edit_user') }}">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>

                                            <!-- Bouton Supprimer -->
                                            @if($user->id !== auth()->id())
                                                <form method="POST" 
                                                      action="{{ route('admin.users.destroy', $user) }}" 
                                                      class="inline" onsubmit="return confirm('{{ __('users.actions.delete_confirm') }}')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="text-red-600 hover:text-red-900 transition-colors" 
                                                            title="{{ __('users.actions.delete_user') }}">
                                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="px-6 py-4 border-t border-gray-200">
                {{ $users->appends(request()->query())->links() }}
            </div>
        @else
            <div class="p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">{{ __('users.empty.title') }}</h3>
                <p class="mt-1 text-sm text-gray-500">
                    @if(request()->hasAny(['search', 'role']))
                        {{ __('users.empty.filtered') }}
                    @else
                        {{ __('users.empty.no_users') }}
                    @endif
                </p>
            </div>
        @endif
    </div>
</div>

@if(session('success'))
<script>
// Faire disparaître automatiquement le message de succès après 5 secondes
setTimeout(function() {
    const successAlert = document.querySelector('.bg-green-50');
    if (successAlert) {
        successAlert.style.transition = 'opacity 0.5s ease-out';
        successAlert.style.opacity = '0';
        setTimeout(() => successAlert.remove(), 500);
    }
}, 5000);
</script>
@endif
@endsection
