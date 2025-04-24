<main class="h-full overflow-y-auto">
    <div class="container px-6 mx-auto">
    <h2 class="my-6 text-2xl font-semibold text-gray-700 dark:text-gray-200">Edit Task</h2>
    
    <div class="w-full flex-1 my-8 ">
        <div class="mx-auto max-w-xl p-16 bg-white rounded-lg shadow-xs dark:bg-gray-800">
            <?= form_open("", array('id' => 'EditTask', 'data-id' => $task['id'])); ?>
                <label class="block text-sm">
                    <span>Title</span>
                    <input type="text" name="title" value="<?= $task['title'] ?>" class="focus:shadow-outline-red form-input">
                    <!-- <span class="form-text">Your password is too short.</span> -->
                </label>

                <label class="block text-sm">
                    <span>Category</span>
                    <select name="category" class="block w-full mt-1 text-sm dark:text-gray-300 dark:border-gray-600 dark:bg-gray-700 form-select focus:border-purple-400 focus:outline-none focus:shadow-outline-purple dark:focus:shadow-outline-gray">
                        <?php foreach($categories as $category): ?>
                            <option <?= ($category['id'] == $task['category']) ? "selected" : null ?> value="<?= $category['id'] ?>"><?= $category['name'] ?></option>
                        <?php endforeach ?>
                    </select>
                </label>

                <label class="block text-sm">
                    <span>Description</span>
                    <input type="text" name="description" value="<?= $task['description'] ?>" class="focus:shadow-outline-red form-input">
                </label>
                <label class="block text-sm">
                    <span>Deadline</span>
                    <input type="date" name="deadline" value="<?= $task['deadline'] ?>" class="focus:shadow-outline-red form-input">
                </label>
                
                
                <button class="mt-5 w-full py-3 hover:bg-indigo-700 flex items-center justify-center focus:shadow-outline focus:outline-none spin" data-send="false">
                    <i class="fa fa-spinner fa-spin"></i> <span class="ml-3">Submit</span>
                </button>                
            <?= form_close() ?>
        </div>
    </div>


    </div>
</main>