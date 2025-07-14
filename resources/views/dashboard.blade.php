<x-app-layout>
  <x-slot name="header">
    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
      {{ __("Dashboard") }}
    </h2>
  </x-slot>

  {{-- <div>
    <label class="block text-sm font-medium mb-2">Pilih Negara:</label>
    <x-select
      class="max-w-md"
      name="country"
      :options="[
          'id' => 'Indonesia',
          'my' => 'Malaysia',
          'sg' => 'Singapore',
          'th' => 'Thailand',
          'ph' => 'Philippines',
          'vn' => 'Vietnam',
      ]"
      placeholder="Pilih negara..."
      searchPlaceholder="Cari negara..."
    />
  </div>

  <label for="city_select">Pilih Kota:</label>
        @php
            $cities = [
                'JKT' => 'Jakarta',
                'SBY' => 'Surabaya',
                'BDG' => 'Bandung',
                'MDN' => 'Medan',
                'DPS' => 'Denpasar'
            ];
        @endphp
        <x-custom-select
            name="city"
            id="city_select"
            :options="$cities"
            placeholder="Pilih Kota"
        /> --}}

  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
          {{ __("You're logged in!") }}
        </div>
      </div>
    </div>
  </div>
  <script>
    // Example: Listen for change events
    document.addEventListener('change', function(e) {
      if (e.target.name && e.target.name.includes('country')) {
        console.log('Country changed to:', e.target.value);
      }
    });
  </script>
</x-app-layout>
