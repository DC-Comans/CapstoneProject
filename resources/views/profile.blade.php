<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="/css/main.css">
        <style>
            .profile-picture {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-picture-default {
            width: 130px;
            height: 130px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid #ccc;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .profile-container {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        .avatar-wrapper {
        width: 130px;
        height: 130px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #ccc;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-image {
        width: 160px; /* Make image larger than container */
        height: 160px;
        object-fit: cover;
    }
        </style>
    </head>
    <body>
    <div style="height: 100vh; background: linear-gradient(to bottom, #ccf5e7, #ffffff);">
        <x-layout>
            
            
            <div class="container mx-auto p-4 " style="color: black">
                <h2 style="font-size: 35" class="text-xl font-bold text-black mb-4">User Profile</h2>

                <div class="flex items-center gap-8">
                {{-- Profile Picture --}}
                <div>
                    @if ($profile->avatar)
                        <img src="{{ asset('storage/' . $profile->avatar) }}"
                            alt="Profile Picture"
                            class="profile-picture" />
                    @else
                    <div class="avatar-wrapper">    
                    <img src="{{ asset('storage/default-avatar.png') }}"
                            alt="Default Picture"
                            class="avatar-image" />
                    </div>
                    @endif
                </div>

                 {{-- User Info --}}
                <p style="font-size: 24" class="text-black"><strong>Username:</strong> {{ $profile->username }}</p>
                <p style="font-size: 24" class="text-black"><strong>Date of Birth:</strong> {{ $profile->DOB }}</p>
                <p style="font-size: 24" class="text-black"><strong>Email:</strong> {{ $profile->email }}</p>

                <a href="/account-edit/{{auth()->user()->id}}"><button>Edit Information</button></a>
            </div>
              
        </div>
        </body>
                </html>
        </x-layout>
