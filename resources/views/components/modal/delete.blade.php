<div class="modal" id="{{ $id }}" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $title }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="POST" id="form-delete">
                @csrf
                @method('DELETE')
                <div class="modal-body">
                    <div class="p-3">
                        <span class="text-danger" style="font-size: 20px">{{ $desc }}</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-danger">Lanjut</button>
                </div>
            </form>
        </div>
    </div>
</div>