 <div tabindex="0"
     class="bg-black bg-transparent bg-opacity-50 modal-overlay z-40 left-0 top-0 right-0 w-full h-full fixed">
     <div class="z-50 relative p-3 mx-auto  my-auto  max-w-full" style="width: 500px;">
         <div class="modal-container bg-white rounded shadow-lg border flex flex-col overflow-hidden px-10 py-10">
             <form :action="url" method="post">
                 <div class="flex justify-center">
                     <button type="button"
                         class="bg-gray-300 text-gray-900 rounded hover:bg-gray-200 px-6 py-2 capitalize focus:outline-none mx-1">{{ __('close') }}</button>
                     <button type="submit"
                         class="bg-blue-500 text-gray-200 rounded hover:bg-blue-400 px-6 py-2 capitalize focus:outline-none mx-1">{{ __('add to') }}
                         {{ $sprint->name }}</button>
                 </div>
             </form>
             <div class="text-center py-6 text-2xl text-gray-700">{{ __('Are you sure ?') }}</div>
             <div class="text-center font-light text-gray-700 mb-8">
                 {{ __('Do you really want to delete this record? This process cannot be undone.') }}
             </div>

         </div>
     </div>
     <div class="z-40 overflow-auto left-0 top-0 bottom-0 right-0 w-full h-full fixed bg-black opacity-50">
     </div>

 </div>
