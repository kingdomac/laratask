 <div>
     @if ($show)
         <div tabindex="0"
             class="bg-black bg-transparent bg-opacity-50 modal-overlay z-40 left-0 top-0 right-0 w-full h-full overflow-y-scroll fixed">
             <div class="z-50 relative p-3 mx-auto  my-auto  max-w-full" style="width: 500px;">
                 <div @click.away="$wire.hide"
                     class="relative modal-container bg-white rounded shadow-lg border flex flex-col px-10 py-10">
                     <i wire:loading.class='opacity-60' wire:target='hide' wire:click="hide"
                         class="absolute top-0 right-0 fa-solid fa-rectangle-xmark ml-2 cursor-pointer"></i>

                     <div class="flex sticky top-0 justify-center text-xs mb-3">
                         <button wire:loading.class='opacity-10' wire:target="addToSprint" wire:click="addToSprint"
                             type="button"
                             class="bg-blue-500 text-gray-200 rounded hover:bg-blue-400 px-6 py-2 capitalize focus:outline-none mx-1">{{ __('add to') }}
                             {{ $sprint->name }}</button>
                     </div>

                     <x-message type="danger" />
                     <div class="text-center font-light text-gray-700 mb-8">
                         @if (count($issues) > 1)
                             <div class="text-left mb-3 text-xs">
                                 <input wire:click="checkAllIssues" type="checkbox" id=all>
                                 <label for="all">{{ __('select all') }}</label> <span wire:loading
                                     wire:target='checkAllIssues'>...</span>
                             </div>
                         @endif
                         @forelse ($issues as $issue)
                             <div class="flex gap-2 text-sm">
                                 <div><input wire:model="selectedIssuesId" type="checkbox" value="{{ $issue->id }}"
                                         id="issue-{{ $issue->id }}"></div>
                                 <div class="flex gap-1" title="{{ $issue->label->name }}">
                                     <div>{{ $issue->id }}-</div>
                                     <div style="color:{{ $issue->label->color }}">{!! $issue->label->icon !!}</div>
                                     <label for="issue-{{ $issue->id }}">{{ $issue->title }}</label>
                                 </div>
                             </div>
                         @empty
                             <div class="text-sm first-letter:uppercase">{{ __('no issues to add.') }}</div>
                         @endforelse
                     </div>

                 </div>
             </div>
             <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50">
             </div>

         </div>
     @endif
 </div>
