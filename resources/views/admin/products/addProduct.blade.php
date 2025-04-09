@extends('admin.layout')

@section('main')
    <div class="row">
        <div class="col-12">
            <div class="card card-default">
                <div class="card-header">
                    <h2>Add Product</h2>
                </div>
                <div class="card-body">

                    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="name">Product Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}">
                            @error('name')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea name="description" id="description" class="form-control" rows="4">{{ old('description') }}</textarea>
                            @error('description')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="category">Category</label>
                            <select name="category_id" id="category" class="form-control">
                                <option value="">Select Category</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="variant">Variants</label>
                            <select name="variant[]" id="variant" class="form-control" multiple onchange="updateVariantOptions()">
                                @foreach ($variants as $variant)
                                    <option value="{{ $variant->id }}" {{ in_array($variant->id, old('variant', [])) ? 'selected' : '' }}>
                                        {{ $variant->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('variant')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div id="variant-options">
                            @if (old('variant_options'))
                                @foreach (old('variant_options') as $variantId => $values)
                                    <div class="form-group variant-option" data-variant-id="{{ $variantId }}">
                                        <label for="variant_option_{{ $variantId }}">Options for Variant {{ $variantId }}</label>
                                        <input type="text" name="variant_options[{{ $variantId }}][]" id="variant_option_{{ $variantId }}" class="form-control" value="{{ implode(',', $values) }}" placeholder="Enter options separated by commas">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div id="variant-prices">
                            @if (old('variant_combinations'))
                                @foreach (old('variant_combinations') as $combination => $data)
                                    <div class="form-group variant-price">
                                        <label for="variant_price_{{ $combination }}">Details for {{ $combination }}</label>
                                        <input type="number" name="variant_combinations[{{ $combination }}][price]" id="variant_price_{{ $combination }}" class="form-control" step="0.01" value="{{ $data['price'] }}" placeholder="Price">
                                        <input type="number" name="variant_combinations[{{ $combination }}][quantity]" id="variant_quantity_{{ $combination }}" class="form-control mt-2" value="{{ $data['quantity'] }}" placeholder="Quantity">
                                    </div>
                                @endforeach
                            @endif
                        </div>
                        <div class="form-group">
                            <label for="price">Price</label>
                            <input type="number" name="price" id="price" class="form-control" step="0.01" value="{{ old('price') }}">
                            @error('price')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}">
                            @error('quantity')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="image">Product Image</label>
                            <input type="file" name="image" id="image" class="form-control-file">
                            @error('image')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_type">Discount Type</label>
                            <select name="discount_type" id="discount_type" class="form-control">
                                <option value="">None</option>
                                <option value="percentage" {{ old('discount_type') == 'percentage' ? 'selected' : '' }}>Percentage</option>
                                <option value="fixed" {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>Fixed Amount</option>
                            </select>
                            @error('discount_type')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_value">Discount Value</label>
                            <input type="number" name="discount_value" id="discount_value" class="form-control" step="0.01" value="{{ old('discount_value') }}">
                            @error('discount_value')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_start">Discount Start Date</label>
                            <input type="date" name="discount_start" id="discount_start" class="form-control" value="{{ old('discount_start') }}">
                            @error('discount_start')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="discount_end">Discount End Date</label>
                            <input type="date" name="discount_end" id="discount_end" class="form-control" value="{{ old('discount_end') }}">
                            @error('discount_end')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="sales">Sales</label>
                            <input type="number" name="sales" id="sales" class="form-control" value="{{ old('sales', 0) }}" min="0">
                            @error('sales')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Add Product</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        function updateVariantOptions() {
            const selectedVariants = Array.from(document.getElementById('variant').selectedOptions).map(option => option.value);
            const variantOptionsContainer = document.getElementById('variant-options');
            variantOptionsContainer.innerHTML = '';

            selectedVariants.forEach(variantId => {
                const optionInput = document.createElement('div');
                optionInput.classList.add('form-group', 'variant-option');
                optionInput.setAttribute('data-variant-id', variantId);
                optionInput.innerHTML = `
                    <label for="variant_option_${variantId}">Options for Variant ${variantId}</label>
                    <input type="text" name="variant_options[${variantId}][]" id="variant_option_${variantId}" class="form-control" placeholder="Enter options separated by commas">
                `;
                variantOptionsContainer.appendChild(optionInput);
            });

            updateVariantPrices();
        }

        function updateVariantPrices() {
            const variantOptionsContainer = document.getElementById('variant-options');
            const variantPricesContainer = document.getElementById('variant-prices');
            variantPricesContainer.innerHTML = '';

            const options = Array.from(variantOptionsContainer.querySelectorAll('.variant-option')).map(optionDiv => {
                const input = optionDiv.querySelector('input');
                const values = input.value.split(',').map(value => value.trim()).filter(value => value);
                return values;
            });

            const combinations = generateCombinations(options);
            combinations.forEach(combination => {
                const combinationKey = combination.join(' - ');
                const priceInput = document.createElement('div');
                priceInput.classList.add('form-group', 'variant-price');
                priceInput.innerHTML = `
                    <label for="variant_price_${combinationKey}">Details for ${combinationKey}</label>
                    <input type="number" name="variant_combinations[${combinationKey}][price]" id="variant_price_${combinationKey}" class="form-control" step="0.01" placeholder="Price">
                    <input type="number" name="variant_combinations[${combinationKey}][quantity]" id="variant_quantity_${combinationKey}" class="form-control mt-2" placeholder="Quantity">
                `;
                variantPricesContainer.appendChild(priceInput);
            });
        }

        function generateCombinations(arrays) {
            if (arrays.length === 0) return [[]];
            const restCombinations = generateCombinations(arrays.slice(1));
            return arrays[0].flatMap(value => restCombinations.map(combination => [value, ...combination]));
        }
    </script>
@endsection
