<div
  class="flex flex-col transition-all h-full overflow-hidden"
  x-data="{ type: 'all', query: @entangle('query') }"
  x-init="setTimeout(() => {
      const conversationElement = document.getElementById('conversation-' + query);
  
      {{-- Scroll to the element --}}
  
      if (conversationElement) {
          conversationElement.scrollIntoView()
      }
  }, 200)
  
  Echo.private('users.{{ auth()->user()->id }}')
  .notification((notification ) => {
    if(notification['type'] == 'App\\Notifications\\MessageRead'|| notification['type'] == 'App\\Notifications\\MessageSent') {
     $dispatch('refresh');
    }
  });
  "
>

  <header class="px-3 z-10 bg-white sticky top-0 w-full py-2">

    <div class="border-b justify-between flex items-center pb-2">

      <div class="flex items-center gap-2">
        <h5 class="font-extrabold text-2xl">Chats</h5>
      </div>

      <button>

        <svg
          class="w-7 h-7"
          xmlns="http://www.w3.org/2000/svg"
          width="16"
          height="16"
          fill="currentColor"
          viewBox="0 0 16 16"
        >
          <path
            d="M6 10.5a.5.5 0 0 1 .5-.5h3a.5.5 0 0 1 0 1h-3a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h7a.5.5 0 0 1 0 1h-7a.5.5 0 0 1-.5-.5zm-2-3a.5.5 0 0 1 .5-.5h11a.5.5 0 0 1 0 1h-11a.5.5 0 0 1-.5-.5z"
          />
        </svg>

      </button>

    </div>

    {{-- Filters --}}

    <div class="flex gap-3 items-center overflow-x-scroll p-2 bg-white">

      <button
        class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border "
        @click="type = 'all'"
        :class="{ 'bg-blue-100 border-0 text-black': type == 'all' }"
      >
        All
      </button>
      <button
        class="inline-flex justify-center items-center rounded-full gap-x-1 text-xs font-medium px-3 lg:px-5 py-1  lg:py-2.5 border "
        @click="type = 'deleted'"
        :class="{ 'bg-blue-100 border-0 text-black': type == 'deleted' }"
      >
        Deleted
      </button>

    </div>

  </header>

  <main class=" overflow-y-scroll overflow-hidden grow  h-full relative " style="contain:content">

    {{-- Chat List --}}
    <ul class="p-2 grid w-full space-y-2">
      @forelse ($conversations as $conversation)
        <li
          class="py-3 hover:bg-gray-100 rounded-2xl dark:hover:bg-gray-700 transition-colors duration-150 flex gap-4 relative w-full cursor-pointer px-2 {{ $conversation->id == $selectedConversation?->id ? 'bg-gray-200/60' : '' }}"
          id="conversation-{{ $conversation->id }}"
          wire:key="{{ $conversation->id }}"
        >
          <a class="shrink-0" href="">
            <x-avatar />
          </a>

          <aside class="grid grid-cols-12 w-full ">
            <a
              class="col-span-11 border-b pb-2 border-gray-200 relative overflow-hidden truncate leading-5 w-full flex-nowrap p-1"
              href="{{ route('chat', $conversation->id) }}"
              wire:navigate
            >
              {{-- Name and Date --}}
              <div class="flex justify-between items-center w-full">
                <h6 class="truncate font-medium tracking-wider text-gray-900">
                  {{ $conversation->getReceiver()->name }}</h6>
                <small
                  class="text-gray 700">{{ $conversation->messages?->last()?->created_at?->shortAbsoluteDiffForHumans() }}</small>
              </div>

              {{-- Message Body --}}

              <div class="flex gap-x-2 items-center">

                @if ($conversation->messages->last()?->sender_id == auth()->id())
                  @if ($conversation->isLastMessageReadByUser())
                    {{-- Double Tick --}}
                    <span>
                      <svg
                        class="bi bi-check2-all"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M12.354 4.354a.5.5 0 0 0-.708-.708L5 10.293 1.854 7.146a.5.5 0 1 0-.708.708l3.5 3.5a.5.5 0 0 0 .708 0zm-4.208 7-.896-.897.707-.707.543.543 6.646-6.647a.5.5 0 0 1 .708.708l-7 7a.5.5 0 0 1-.708 0"
                        />
                        <path d="m5.354 7.146.896.897-.707.707-.897-.896a.5.5 0 1 1 .708-.708" />
                      </svg>
                    </span>
                  @else
                    {{-- Single Tick --}}
                    <span>
                      <svg
                        class="bi bi-check2"
                        xmlns="http://www.w3.org/2000/svg"
                        width="16"
                        height="16"
                        fill="currentColor"
                        viewBox="0 0 16 16"
                      >
                        <path
                          d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0"
                        />
                      </svg>
                    </span>
                  @endif
                @endif

                <p class="grow truncate text-sm font-[100]">
                  {{ $conversation->messages?->last()?->body ?? '' }}
                </p>

                {{-- Unread Count --}}

                @if ($conversation->unreadMessagesCount() > 0)
                  <span
                    class="font-bold p-px px-2 text-xs shrink-0 rounded-full bg-blue-500 text-white"
                  >
                    {{ $conversation->unreadMessagesCount() }}
                  </span>
                @endif
              </div>
            </a>

            {{-- Dropdown --}}
            <div class="col-span-1 flex flex-col text-center my-auto">
              <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                  <button><svg
                      class="bi bi-three-dots-vertical w-6 h-6 text-gray-700"
                      xmlns="http://www.w3.org/2000/svg"
                      width="16"
                      height="16"
                      fill="currentColor"
                      viewBox="0 0 16 16"
                    >
                      <path
                        d="M9.5 13a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0m0-5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"
                      />
                    </svg></button>
                </x-slot>

                <x-slot name="content">
                  <div class="w-full p-1">
                    <button
                      class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-gray-100 transition-all duration-150 ease-in-out focus:outline-none focus-bg-gray-100"
                    >
                      <span>
                        <svg
                          class="bi bi-person-circle"
                          xmlns="http://www.w3.org/2000/svg"
                          width="16"
                          height="16"
                          fill="currentColor"
                          viewBox="0 0 16 16"
                        >
                          <path d="M11 6a3 3 0 1 1-6 0 3 3 0 0 1 6 0" />
                          <path fill-rule="evenodd"
                            d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8m8-7a7 7 0 0 0-5.468 11.37C3.242 11.226 4.805 10 8 10s4.757 1.225 5.468 2.37A7 7 0 0 0 8 1"
                          />
                        </svg>
                      </span>
                      View Profile
                    </button>
                    <button
                    onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
                    wire:click="deleteByUser('{{ encrypt($conversation->id)  }}')"
                      class="items-center gap-3 flex w-full px-4 py-2 text-left text-sm leading-5 text-gray-500 hover:bg-gray-100 transition-all duration-150 ease-in-out focus:outline-none focus-bg-gray-100"
                    >
                      <span>
                        <svg
                          class="bi bi-trash-fill"
                          xmlns="http://www.w3.org/2000/svg"
                          width="16"
                          height="16"
                          fill="currentColor"
                          viewBox="0 0 16 16"
                        >
                          <path
                            d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5M8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5m3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0"
                          />
                        </svg>
                      </span>
                      Delete
                    </button>
                  </div>
                </x-slot>
              </x-dropdown>
            </div>
          </aside>
        </li>
      @empty
      @endforelse
    </ul>
  </main>
</div>
