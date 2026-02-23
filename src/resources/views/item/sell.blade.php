@extends('layouts.app')
@section('css')
    <link rel="stylesheet" href="{{ asset('css/item/sell.css') }}">
@endsection
@section('content')
    <form action="{{ route('sell.store') }}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="sell__item">

            <div class="title">
                <h1>商品の出品</h1>
            </div>

            <div class="item__image">
                <label class="label">商品画像</label>
            </div>

            <div class="sell__image" id="sell__image">
                <label>
                    <span id="upload-text">画像を選択する</span>
                    {{-- javaScriptで、画像を選択した際に、[画像を選択する]を消して画像だけを表示させます --}}
                    <div id="preview-container"></div>

                    <input class="image__input" name="image" accept=".png, .jpeg" type="file"
                        onchange="previewFile(this);">
                </label>
            </div>

            <div class="error">
                @error('image')
                    {{ $message }}
                @enderror
            </div>

            <h2>商品詳細</h2>
            <hr>

            <div class="sell__item-category">
                <label class="label">カテゴリー</label>
            </div>

            <div class="item__category">
                @foreach ($categories as $category)
                    <div class="category-wrapper">
                        <input class="checkbox" type="checkbox" name="category_ids[]" value="{{ $category->id }}"
                            id="cat{{ $category->id }}">
                        <label class="item__checkbox" for="cat{{ $category->id }}">
                            {{ $category->name }}
                        </label>
                    </div>
                @endforeach
            </div>

            <div class="error">
                @error('category_ids')
                    {{ $message }}
                @enderror
            </div>

            <div class="sell__item-condition">
                <div class="item__condition">
                    <label class="label">商品の状態</label>
                </div>

                <select name="condition_id" id="condition" class="form-control">
                    <option value="" disabled selected>選択してください</option>
                    @foreach ($conditions as $condition)
                        <option value="{{ $condition->id }}">
                            {{ $condition->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="error">
                @error('condition_id')
                    {{ $message }}
                @enderror
            </div>

            <div class="sell__name-description">
                <div class="item__exposition">
                    <h2>商品名と説明</h2>
                </div>
                <hr>

                <div class="item__name">
                    <label class="label">商品名</label>
                    <input class="input_text" type="text" name="name" value="{{ old('name') }}">
                </div>

                <div class="error">
                    @error('name')
                        {{ $message }}
                    @enderror
                </div>

                <div class="item__brand-name">
                    <label class="label">ブランド名</label>
                    <input class="input_text" type="text" name="brand_name" value="{{ old('brand_name') }}">
                </div>

                <div class="item__description">
                    <label class="label">商品の説明</label>
                    <textarea class="textarea" name="description" rows="10">{{ old('description') }}</textarea>
                </div>

                <div class="error">
                    @error('description')
                        {{ $message }}
                    @enderror
                </div>

                <div class="item__price">
                    <label class="label">販売価格</label>
                    <div class="price-input-container">
                        <span class="currency-symbol">￥</span>
                        <input class="input_price" type="text" name="price" value="{{ old('price') }}">
                    </div>
                </div>

                <div class="error">
                    @error('price')
                        {{ $message }}
                    @enderror
                </div>

                <div class="sell__button">
                    <button class="button" type="submit">出品する</button>
                </div>
            </div>
        </div>
    </form>

    <script>
        function previewFile(input) {
            const file = input.files[0];
            const text = document.getElementById('upload-text');
            const container = document.getElementById('preview-container');
            const sellImageDiv = document.getElementById('sell__image');

            if (file) {
                const reader = new FileReader();
                // ファイルの読み込みが完了した時の処理
                reader.onload = function(e) {
                    if (text) {
                        text.style.display = 'none';
                    }

                    if (sellImageDiv) {
                        sellImageDiv.style.border = 'none';
                    }

                    container.innerHTML = `
                <img src="${e.target.result}" 
                     style="width: 100%; height: 100%; object-fit: contain;">`;
                }

                reader.readAsDataURL(file);
            }
        }
    </script>
@endsection
