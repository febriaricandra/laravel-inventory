<div class="flex space-x-2">
    <button onclick="editCategory({{ $row->id }})" class="px-4 py-2 text-sm text-white bg-blue-500 rounded hover:bg-blue-600">
        Edit
    </button>
    <button onclick="deleteCategory({{ $row->id }})" class="px-4 py-2 text-sm text-white bg-red-500 rounded hover:bg-red-600">
        Delete
    </button>
</div>