<div class="d-flex justify-content-left">
    <form action="{{ route('destroy-tempalls', ['id' => $tempall->id]) }}" method="POST" class="delete-form">
        @csrf
        @method('delete')
        <button type="submit" class="btn btn-danger btn-sm me-2 btn-delete shadow" style="padding: 7px 7px; font-size: 14px;">
            <i class="bi-trash"></i>
        </button>
    </form>
</div>
