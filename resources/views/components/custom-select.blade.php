
@props(['options' => [], 'selected' => null, 'placeholder' => 'Pilih opsi...', 'name' => 'select', 'id' => null])

<div class="custom-select-wrapper" id="custom-select-wrapper-{{ $id }}">
    <div class="custom-select-trigger" data-id="{{ $id }}">
        <span class="custom-select-placeholder">{{ $placeholder }}</span>
        <input type="hidden" name="{{ $name }}" id="{{ $id }}" value="{{ $selected }}">
        <div class="arrow"></div>
    </div>
    <div class="custom-options">
        <div class="custom-search">
            <input type="text" class="custom-search-input" placeholder="Cari..." data-id="{{ $id }}">
        </div>
        @foreach ($options as $value => $label)
            <div class="custom-option @if ($selected == $value) selected @endif" data-value="{{ $value }}">{{ $label }}</div>
        @endforeach
        @if (empty($options))
            <div class="custom-option no-results" style="display: none;">Tidak ada hasil ditemukan</div>
        @endif
    </div>
</div>

<style>
    /* Basic Styling for the Custom Select */
    .custom-select-wrapper {
        position: relative;
        width: 100%;
        max-width: 300px; /* Adjust as needed */
    }

    .custom-select-trigger {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 15px;
        border: 1px solid #ccc;
        border-radius: 5px;
        cursor: pointer;
        background-color: #fff;
    }

    .custom-select-trigger .arrow {
        border: solid black;
        border-width: 0 2px 2px 0;
        display: inline-block;
        padding: 3px;
        transform: rotate(45deg);
        -webkit-transform: rotate(45deg);
        transition: transform 0.3s ease;
    }

    .custom-select-trigger.active .arrow {
        transform: rotate(-135deg);
        -webkit-transform: rotate(-135deg);
    }

    .custom-options {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        border: 1px solid #ccc;
        border-top: none;
        border-radius: 0 0 5px 5px;
        background-color: #fff;
        max-height: 200px; /* Scrollable if many options */
        overflow-y: auto;
        z-index: 999;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        display: none; /* Hidden by default */
    }

    .custom-options.open {
        display: block;
    }

    .custom-search {
        padding: 10px;
        border-bottom: 1px solid #eee;
    }

    .custom-search-input {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 3px;
        box-sizing: border-box; /* Include padding in width */
    }

    .custom-option {
        padding: 10px 15px;
        cursor: pointer;
    }

    .custom-option:hover,
    .custom-option.selected {
        background-color: #f0f0f0;
    }

    .custom-option.no-results {
        text-align: center;
        color: #888;
        padding: 15px;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.custom-select-wrapper').forEach(function(wrapper) {
            const selectTrigger = wrapper.querySelector('.custom-select-trigger');
            const optionsContainer = wrapper.querySelector('.custom-options');
            const hiddenInput = wrapper.querySelector('input[type="hidden"]');
            const placeholderSpan = wrapper.querySelector('.custom-select-placeholder');
            const searchInput = wrapper.querySelector('.custom-search-input');
            const customOptions = optionsContainer.querySelectorAll('.custom-option');
            const noResultsOption = optionsContainer.querySelector('.custom-option.no-results');

            // Set initial selected value if any
            const initialSelectedValue = hiddenInput.value;
            if (initialSelectedValue) {
                const initialSelectedOption = optionsContainer.querySelector(`.custom-option[data-value="${initialSelectedValue}"]`);
                if (initialSelectedOption) {
                    placeholderSpan.textContent = initialSelectedOption.textContent;
                }
            }

            selectTrigger.addEventListener('click', function () {
                optionsContainer.classList.toggle('open');
                selectTrigger.classList.toggle('active');
                searchInput.focus(); // Focus on search input when opened
            });

            optionsContainer.addEventListener('click', function (e) {
                if (e.target.classList.contains('custom-option') && !e.target.classList.contains('no-results')) {
                    const selectedValue = e.target.dataset.value;
                    const selectedText = e.target.textContent;

                    hiddenInput.value = selectedValue;
                    placeholderSpan.textContent = selectedText;

                    // Remove 'selected' class from previously selected option
                    optionsContainer.querySelectorAll('.custom-option.selected').forEach(function(el) {
                        el.classList.remove('selected');
                    });
                    // Add 'selected' class to the newly selected option
                    e.target.classList.add('selected');

                    optionsContainer.classList.remove('open');
                    selectTrigger.classList.remove('active');
                }
            });

            searchInput.addEventListener('input', function() {
                const searchTerm = searchInput.value.toLowerCase();
                let hasResults = false;

                customOptions.forEach(function(option) {
                    const optionText = option.textContent.toLowerCase();
                    if (optionText.includes(searchTerm)) {
                        option.style.display = '';
                        hasResults = true;
                    } else {
                        option.style.display = 'none';
                    }
                });

                if (noResultsOption) { // Check if noResultsOption exists
                    if (hasResults) {
                        noResultsOption.style.display = 'none';
                    } else {
                        noResultsOption.style.display = 'block';
                    }
                }
            });

            // Close when clicking outside
            document.addEventListener('click', function (e) {
                if (!wrapper.contains(e.target)) {
                    optionsContainer.classList.remove('open');
                    selectTrigger.classList.remove('active');
                }
            });
        });
    });
</script>