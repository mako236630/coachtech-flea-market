@extends('layouts.app')

@section('css')
    <link rel="stylesheet" href="{{ asset('css/user/profile-setting.css') }}">
@endsection

@section('content')
    <main>

        <div class="profile__setting">
            <div class="title">
                <h1>プロフィール設定</h1>
            </div>

            <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
                @csrf

                @if (session('error'))
                    <div class="session__error">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="profile__image-setting">
                    <div id="image-preview-area">
                        @if ($user->image)
                            <img class="profile__image" src="{{ asset('storage/' . $user->image) }}">
                        @else
                            <div class="user-no-img"></div>
                        @endif
                    </div>
                    <label class="image__setting">
                        画像を選択する
                        <input class="image__input" name="image" accept=".png, .jpeg" type="file"
                            onchange="previewFile(this);">
                    </label>

                    <div class="error" style="color: red">
                        @error('image')
                            {{ $message }}
                        @enderror
                    </div>

                </div>


                <label class="label">ユーザー名</label>
                <input class="input" type="text" name="name" value="{{ old('name', $user->name) }}">

                <div class="error" style="color: red">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>

                <label class="label">郵便番号</label>
                <input class="input" type="text" name="postcode"
                    value="{{ old('postcode', optional($address)->postcode) }}">

                <div class="error" style="color: red">
                    @error('postcode')
                        {{ $message }}
                    @enderror
                </div>


                <label class="label">住所</label>
                <input class="input" type="text" name="address"
                    value="{{ old('address', optional($address)->address) }}">

                <div class="error" style="color: red">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>

                <label class="label">建物名</label>
                <input class="input" type="text" name="building"
                    value="{{ old('building', optional($address)->building) }}">

                <div class="button__container">
                    <button class="button" type="submit">更新する</button>
                </div>

            </form>
        </div>

    </main>

    <script>
        function previewFile(input) {
            if (input.files && input.files[0]) {
                var fileData = new FileReader();

                fileData.onload = function() {
                    // 選択した画像をプレビュー表示してます
                    var area = document.getElementById('image-preview-area');
                    area.innerHTML = '<img class="profile__image" src="' + fileData.result + '">';
                };

                fileData.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
