<x-app-layout>
    <x-slot name="header">
        <h1 class="m-0 text-white">{{ __('Profile') }}</h1>
    </x-slot>

    <div class="row g-3">
        <div class="col-xl-6">
            @include('profile.partials.update-profile-information-form')
        </div>
        <div class="col-xl-6">
            @include('profile.partials.update-password-form')
        </div>
        <div class="col-12">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</x-app-layout>
