<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Add Category</h2>
    
    <div class="w-full flex-1 my-8 ">
        <div class="mx-auto max-w-xl p-16 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <?= form_open("", array('id' => 'CreateTaskCategory')); ?>
                <label class="block text-sm">
                    <span>Category Name</span>
                    <input type="text" name="name" class="focus:shadow-outline-red form-input">
                </label>
                
                <button class="mt-5 w-full py-3 hover:bg-indigo-700 flex items-center justify-center focus:shadow-outline focus:outline-none spin" data-send="false">
                    <i class="fa fa-spinner fa-spin"></i> <span class="ml-3">Submit</span>
                </button>
            <?= form_close() ?>
        </div>
    </div>
    </div>
</main>