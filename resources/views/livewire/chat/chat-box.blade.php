<div
  class="w-full overflow-hidden"
  x-data="{
      height: 0,
      conversationElement: document.getElementById('conversation'),
      markAsRead: null
  }"
  x-init="
  height = conversationElement.scrollHeight;
  $nextTick(() => { conversationElement.scrollTop = height });
  
  Echo.private('users.{{ auth()->user()->id }}')
      .notification((notification) => {
          if (notification['type'] == 'App\\Notifications\\MessageRead' && notification['conversation_id'] == {{ $this->selectedConversation->id }}) {
              markAsRead = true
          }
      });
      
      "
  @scroll-bottom.window="$nextTick(()=> conversationElement.scrollTop= conversationElement.scrollHeight);"
>

  <div class="border-b flex flex-col overflow-y-scroll grow h-full">
    {{-- Header --}}
    <header class="w-full sticky inset-x-0 flex pb-[5px] pt-[5px] top-0 z-10 bg-white border-b">
      <div class="flex w-full items-center px-2 lg:px-4 gap-2 md:gap-5">
        <a class="shrink-0 md:hidden" href="">
          <svg
            class="bi bi-arrow-left-short"
            xmlns="http://www.w3.org/2000/svg"
            width="16"
            height="16"
            fill="currentColor"
            viewBox="0 0 16 16"
          >
            <path fill-rule="evenodd"
              d="M12 8a.5.5 0 0 1-.5.5H5.707l2.147 2.146a.5.5 0 0 1-.708.708l-3-3a.5.5 0 0 1 0-.708l3-3a.5.5 0 1 1 .708.708L5.707 7.5H11.5a.5.5 0 0 1 .5.5"
            />
          </svg>
        </a>

        {{-- Avatar --}}
        <div class="shrink-0">
          <x-avatar class="h-9 w-9 lg:h-11 lg:w-11" />
        </div>

        <h6 class="font-bold truncate">{{ $selectedConversation->getReceiver()->email }}</h6>
      </div>
    </header>

    {{-- Body --}}
    <main
      class="flex flex-col gap-3 p-2.5 overflow-y-auto flex-grow overscroll-contain overflow-x-hidden"
      id="conversation"
      x-data="{
          height: 0,
      }"
      @scroll="
      scrollTop = $el.scrollTop;
      if(scrollTop <= 0) {
        $dispatch('loadMore');
      }
      "
      @update-chat-height.window="
        newHeight = $el.scrollHeight;

        oldHeight = height;
        $el.scrollTop = newHeight- oldHeight;

        height = newHeight;
      "
    >

      @php
        $previousMessage = null;
      @endphp

      @forelse ($loadedMessages as $key => $message)
        {{-- keep track of previous message --}}
        @if ($key > 0)
          @php
            $previousMessage = $loadedMessages->get($key - 1);
          @endphp
        @endif

        <div wire:key="{{ time() . $key }}" @class([
            'max-w-[85%] md:max-w-[78%] flex w-auto gap-2 relative mt-2',
            'ml-auto' => $message->sender_id == auth()->id(),
        ])>
          {{-- avatar  --}}
          <div @class([
              'shrink-0',
              'invisible' => $previousMessage?->sender_id == $message->sender_id,
              'hidden' => $message->sender_id == auth()->id(),
          ])>
            <x-avatar />
          </div>

          {{-- message body --}}
          <div @class([
              'flex flex-wrap text-[15px] rounded-xl p-2.5 flex-col text-black bg-[#f6f6f8fb]',
              'rounded-bl-none border border-gray-200/40' => !(
                  $message->sender_id == auth()->id()
              ),
              'rounded-br-none bg-blue-500/80 text-white' =>
                  $message->sender_id == auth()->id(),
          ])>
            <p
              class="whitespace-normal truncate text-sm md:text-base tracking-wide lg:tracking-normal">
              {{ $message->body }}
            </p>

            <div class="ml-auto flex gap-2">
              <p @class([
                  'text-xs',
                  'text-gray-500' => !($message->sender_id == auth()->id()),
                  'text-white' => $message->sender_id == auth()->id(),
              ])>
                {{ $message->created_at->format('g:i a') }}
              </p>

              {{-- message status, only show if message belongs auth --}}

              @if ($message->sender_id == auth()->id())
                <div x-data="{ markAsRead:@json($message->isRead()) }">

                  {{-- Double Tick --}}
                  <span
                    x-cloak
                    x-show="markAsRead"
                    @class(['text-gray-200'])
                  >
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

                  {{-- Single Tick --}}
                  <span x-show="!markAsRead" @class(['text-gray-200'])>
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
                </div>
              @endif
            </div>
          </div>
        </div>
      @empty
      @endforelse

    </main>

    {{-- Send Message --}}
    <footer class="shrink-0 z-10 bg-white inset-x-0">

      <div class="p-2 border-t">

        <form
          x-data="{ body: @entangle('body') }"
          @submit.prevent="$wire.sendMessage"
          method="POST"
          autocapitalize="off"
        >
          @csrf
          <input
            name=""
            type="hidden"
            style="display: none;"
            autocomplete="off"
          >

          <div class="grid grid-cols-12">
            <input
              class="col-span-10 bg-gray-100 border-0 outline-0 focus:border-0 focus:ring-0 hover:ring-0 rounded-lg focus-outline-none"
              id=" "
              name=""
              type="text"
              x-model="body"
              autocomplete="off"
              x-data="{ focus: true }"
              x-trap="focus"
              placeholder="write your message here"
              maxlength="1700"
            >

            <button
              class="col-span-2"
              type="submit"
              x-bind:disabled="!body?.trim()"
            >Send</button>
          </div>
        </form>

        @error('body')
          <p>{{ $message }}</p>
        @enderror
      </div>
    </footer>

  </div>
</div>
