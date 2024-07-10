<div class="d-flex justify-content-left">
    <a href="{{ route('edit-alls', ['id' => $all->id]) }}" class="btn btn-blue btn-sm me-2 shadow">
        <i class="bi-pencil"></i>
    </a>
    <form action="{{ route('destroy-alls', ['id' => $all->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm btn-delete shadow">
            <i class="bi-trash"></i>
        </button>
    </form>
</div>
