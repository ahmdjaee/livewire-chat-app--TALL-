
@props([
    'options' => [],
    'selected' => null,
    'placeholder' => 'Pilih opsi...',
    'searchPlaceholder' => 'Cari...',
    'name' => 'select',
    'id' => null,
    'class' => '',
    'searchable' => true,
    'disabled' => false,
    'required' => false
])

@php
    $componentId = $id ?? 'custom-select-' . uniqid();
    $inputId = $componentId . '-input';
@endphp

<div class="custom-select-wrapper {{ $class }}" id="{{ $componentId }}">
    <!-- Hidden input untuk form submission -->
    <input type="hidden" name="{{ $name }}" value="{{ $selected }}" id="{{ $inputId }}" 
           @if($required) required @endif>
    
    <!-- Select trigger -->
    <div class="custom-select-trigger" 
         @if($disabled) disabled @endif
         tabindex="0">
        <span class="custom-select-label">{{ $placeholder }}</span>
        <svg class="custom-select-arrow" width="12" height="8" viewBox="0 0 12 8">
            <path d="M1 1l5 5 5-5" stroke="currentColor" stroke-width="2" fill="none" fill-rule="evenodd"/>
        </svg>
    </div>
    
    <!-- Dropdown -->
    <div class="custom-select-dropdown">
        @if($searchable)
            <div class="custom-select-search">
                <input type="text" 
                       class="custom-select-search-input" 
                       placeholder="{{ $searchPlaceholder }}"
                       autocomplete="off">
                <svg class="custom-select-search-icon" width="16" height="16" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
            </div>
        @endif
        
        <div class="custom-select-options">
            @foreach($options as $value => $label)
                <div class="custom-select-option" 
                     data-value="{{ $value }}"
                     @if($selected == $value) data-selected="true" @endif>
                    {{ $label }}
                </div>
            @endforeach
        </div>
        
        <div class="custom-select-no-results" style="display: none;">
            Tidak ada hasil ditemukan
        </div>
    </div>
</div>

<style>
.custom-select-wrapper {
    position: relative;
    width: 100%;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.custom-select-trigger {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 12px 16px;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
    outline: none;
    min-height: 48px;
}

.custom-select-trigger:hover {
    border-color: #cbd5e1;
}

.custom-select-trigger:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.custom-select-trigger[disabled] {
    background: #f8fafc;
    cursor: not-allowed;
    opacity: 0.6;
}

.custom-select-trigger.active {
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.custom-select-trigger.active .custom-select-arrow {
    transform: rotate(180deg);
}

.custom-select-label {
    color: #374151;
    flex: 1;
    text-align: left;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.custom-select-label.placeholder {
    color: #9ca3af;
}

.custom-select-arrow {
    color: #6b7280;
    transition: transform 0.2s ease;
    flex-shrink: 0;
}

.custom-select-dropdown {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    margin-top: 4px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    z-index: 1000;
    display: none;
    max-height: 300px;
    overflow: hidden;
}

.custom-select-dropdown.show {
    display: block;
}

.custom-select-search {
    position: relative;
    padding: 12px;
    border-bottom: 1px solid #e2e8f0;
}

.custom-select-search-input {
    width: 100%;
    padding: 8px 12px 8px 40px;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    outline: none;
    font-size: 14px;
}

.custom-select-search-input:focus {
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.1);
}

.custom-select-search-icon {
    position: absolute;
    left: 24px;
    top: 50%;
    transform: translateY(-50%);
    color: #9ca3af;
    pointer-events: none;
}

.custom-select-options {
    max-height: 200px;
    overflow-y: auto;
}

.custom-select-option {
    padding: 12px 16px;
    cursor: pointer;
    transition: background-color 0.15s ease;
    border-bottom: 1px solid #f1f5f9;
}

.custom-select-option:last-child {
    border-bottom: none;
}

.custom-select-option:hover {
    background-color: #f8fafc;
}

.custom-select-option[data-selected="true"] {
    background-color: #eff6ff;
    color: #1d4ed8;
    font-weight: 500;
}

.custom-select-option[data-selected="true"]:hover {
    background-color: #dbeafe;
}

.custom-select-option.hidden {
    display: none;
}

.custom-select-no-results {
    padding: 16px;
    text-align: center;
    color: #6b7280;
    font-style: italic;
}

/* Scrollbar styling */
.custom-select-options::-webkit-scrollbar {
    width: 6px;
}

.custom-select-options::-webkit-scrollbar-track {
    background: #f1f5f9;
}

.custom-select-options::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.custom-select-options::-webkit-scrollbar-thumb:hover {
    background: #94a3b8;
}
</style>

<script>
// Function to initialize custom select
function initCustomSelect(componentId) {
    const selectWrapper = document.getElementById(componentId);
    if (!selectWrapper) return;
    
    // Check if already initialized
    if (selectWrapper.hasAttribute('data-initialized')) return;
    selectWrapper.setAttribute('data-initialized', 'true');
    
    const trigger = selectWrapper.querySelector('.custom-select-trigger');
    const dropdown = selectWrapper.querySelector('.custom-select-dropdown');
    const hiddenInput = selectWrapper.querySelector('input[type="hidden"]');
    const label = selectWrapper.querySelector('.custom-select-label');
    const searchInput = selectWrapper.querySelector('.custom-select-search-input');
    const options = selectWrapper.querySelectorAll('.custom-select-option');
    const noResults = selectWrapper.querySelector('.custom-select-no-results');
    
    let isOpen = false;
    let selectedValue = hiddenInput.value || '';
    let selectedOption = null;
    
    // Initialize
    if (selectedValue) {
        selectedOption = selectWrapper.querySelector(`[data-value="${selectedValue}"]`);
        if (selectedOption) {
            updateLabel(selectedOption.textContent);
        }
    } else {
        label.classList.add('placeholder');
    }
    
    // Event delegation for click outside
    function handleClickOutside(e) {
        if (!selectWrapper.contains(e.target)) {
            closeDropdown();
        }
    }
    
    // Toggle dropdown
    trigger.addEventListener('click', function(e) {
        if (trigger.hasAttribute('disabled')) return;
        
        e.preventDefault();
        e.stopPropagation();
        
        if (isOpen) {
            closeDropdown();
        } else {
            openDropdown();
        }
    });
    
    // Keyboard navigation
    trigger.addEventListener('keydown', function(e) {
        if (trigger.hasAttribute('disabled')) return;
        
        switch(e.key) {
            case 'Enter':
            case ' ':
                e.preventDefault();
                if (isOpen) {
                    closeDropdown();
                } else {
                    openDropdown();
                }
                break;
            case 'Escape':
                if (isOpen) {
                    e.preventDefault();
                    closeDropdown();
                }
                break;
            case 'ArrowDown':
                e.preventDefault();
                if (!isOpen) {
                    openDropdown();
                } else {
                    navigateOptions('down');
                }
                break;
            case 'ArrowUp':
                e.preventDefault();
                if (isOpen) {
                    navigateOptions('up');
                }
                break;
        }
    });
    
    // Option selection
    options.forEach(option => {
        option.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            selectOption(this);
        });
    });
    
    // Search functionality
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            let hasVisibleOptions = false;
            
            options.forEach(option => {
                const text = option.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    option.classList.remove('hidden');
                    hasVisibleOptions = true;
                } else {
                    option.classList.add('hidden');
                }
            });
            
            noResults.style.display = hasVisibleOptions ? 'none' : 'block';
        });
        
        searchInput.addEventListener('keydown', function(e) {
            switch(e.key) {
                case 'ArrowDown':
                    e.preventDefault();
                    navigateOptions('down');
                    break;
                case 'ArrowUp':
                    e.preventDefault();
                    navigateOptions('up');
                    break;
                case 'Enter':
                    e.preventDefault();
                    const focused = selectWrapper.querySelector('.custom-select-option.focused');
                    if (focused && !focused.classList.contains('hidden')) {
                        selectOption(focused);
                    }
                    break;
                case 'Escape':
                    e.preventDefault();
                    closeDropdown();
                    break;
            }
        });
    }
    
    function openDropdown() {
        if (trigger.hasAttribute('disabled')) return;
        
        // Close other dropdowns first
        document.querySelectorAll('.custom-select-dropdown.show').forEach(dd => {
            if (dd !== dropdown) {
                dd.classList.remove('show');
                dd.parentElement.querySelector('.custom-select-trigger').classList.remove('active');
            }
        });
        
        isOpen = true;
        dropdown.classList.add('show');
        trigger.classList.add('active');
        
        // Add click outside listener
        document.addEventListener('click', handleClickOutside);
        
        if (searchInput) {
            searchInput.focus();
            searchInput.value = '';
            
            // Reset search
            options.forEach(option => {
                option.classList.remove('hidden');
            });
            noResults.style.display = 'none';
        }
        
        // Focus selected option
        if (selectedOption) {
            selectedOption.classList.add('focused');
            selectedOption.scrollIntoView({ block: 'nearest' });
        }
    }
    
    function closeDropdown() {
        isOpen = false;
        dropdown.classList.remove('show');
        trigger.classList.remove('active');
        
        // Remove click outside listener
        document.removeEventListener('click', handleClickOutside);
        
        // Remove focus from all options
        options.forEach(option => {
            option.classList.remove('focused');
        });
        
        trigger.focus();
    }
    
    function selectOption(option) {
        // Remove previous selection
        if (selectedOption) {
            selectedOption.removeAttribute('data-selected');
        }
        
        // Set new selection
        selectedOption = option;
        selectedValue = option.dataset.value;
        
        option.setAttribute('data-selected', 'true');
        hiddenInput.value = selectedValue;
        
        updateLabel(option.textContent);
        closeDropdown();
        
        // Dispatch change event for Livewire
        const event = new Event('change', { bubbles: true });
        hiddenInput.dispatchEvent(event);
        
        // Dispatch input event for Livewire
        const inputEvent = new Event('input', { bubbles: true });
        hiddenInput.dispatchEvent(inputEvent);
        
        // Livewire specific event
        if (window.Livewire) {
            window.Livewire.dispatch('input', { name: hiddenInput.name, value: selectedValue });
        }
    }
    
    function updateLabel(text) {
        label.textContent = text;
        label.classList.remove('placeholder');
    }
    
    function navigateOptions(direction) {
        const visibleOptions = Array.from(options).filter(opt => 
            !opt.classList.contains('hidden')
        );
        
        if (visibleOptions.length === 0) return;
        
        const currentFocused = selectWrapper.querySelector('.custom-select-option.focused');
        let nextIndex = 0;
        
        if (currentFocused) {
            const currentIndex = visibleOptions.indexOf(currentFocused);
            if (direction === 'down') {
                nextIndex = currentIndex + 1;
                if (nextIndex >= visibleOptions.length) nextIndex = 0;
            } else {
                nextIndex = currentIndex - 1;
                if (nextIndex < 0) nextIndex = visibleOptions.length - 1;
            }
            
            currentFocused.classList.remove('focused');
        }
        
        const nextOption = visibleOptions[nextIndex];
        nextOption.classList.add('focused');
        nextOption.scrollIntoView({ block: 'nearest' });
    }
    
    // Public method to update value programmatically
    selectWrapper.updateValue = function(value) {
        const option = selectWrapper.querySelector(`[data-value="${value}"]`);
        if (option) {
            selectOption(option);
        }
    };
    
    // Public method to get current value
    selectWrapper.getValue = function() {
        return selectedValue;
    };
    
    // Clean up function
    selectWrapper.destroy = function() {
        document.removeEventListener('click', handleClickOutside);
        selectWrapper.removeAttribute('data-initialized');
    };
}

// Initialize on DOM ready
document.addEventListener('DOMContentLoaded', function() {
    initCustomSelect('{{ $componentId }}');
});

// Initialize after Livewire updates
document.addEventListener('livewire:navigated', function() {
    initCustomSelect('{{ $componentId }}');
});

// For older Livewire versions
document.addEventListener('livewire:load', function() {
    initCustomSelect('{{ $componentId }}');
});

// For Livewire v3
document.addEventListener('livewire:init', function() {
    initCustomSelect('{{ $componentId }}');
});

// Re-initialize after Livewire updates
if (window.Livewire) {
    window.Livewire.hook('morph.updated', () => {
        setTimeout(() => {
            initCustomSelect('{{ $componentId }}');
        }, 100);
    });
}
</script>