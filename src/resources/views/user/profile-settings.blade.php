@extends('layouts.app')
@section('content')
    <main>
        <div>
            <h1>プロフィール設定</h1>
        </div>
        <form action="{{ route('profile.update') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div>
                {{-- 画像をプレビュー表示する為、srcでimageのパスを取得しています　--}}
                <img id="preview"src="{{ $user->image ? asset('storage/' . $user->image) : asset('images/no-image.png') }}" width="200">
                <input name="image" accept=".png, .jpeg" type="file" onchange="previewFile(this);">
            </div>

            <div class="form__error" style="color: red">
                @error('image')
                    {{ $message }}
                @enderror
            </div>

            <div>
                <label>ユーザー名</label>
                <input type="text" name="name" value="{{ old('name', $user->name) }}"><br>

                <div class="form__error" style="color: red">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>

                <label>郵便番号</label>
                <input type="text" name="postcode" value="{{ old('postcode', optional($address)->postcode) }}"><br>

                <div class="form__error" style="color: red">
                    @error('postcode')
                        {{ $message }}
                    @enderror
                </div>


                <label>住所</label>
                <input type="text" name="address" value="{{ old('address', optional($address)->address) }}"><br>

                <div class="form__error" style="color: red">
                    @error('address')
                        {{ $message }}
                    @enderror
                </div>

                <label>建物名</label>
                <input type="text" name="building" value="{{ old('building', optional($address)->building) }}">
            </div>

            <div>
                <button type="submit">更新する</button>
            </div>
        </form>
    </main>

    <script>
        function previewFile(input) {
            if (input.files && input.files[0]) {
                var fileData = new FileReader();

                fileData.onload = function() {
                    // 選択した画像をプレビュー表示してます
                    document.getElementById('preview').src = fileData.result;
                };

                fileData.readAsDataURL(input.files[0]);
            }
        }
    </script>
@endsection
