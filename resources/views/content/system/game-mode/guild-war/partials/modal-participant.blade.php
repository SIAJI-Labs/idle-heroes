<form action="{{ route('s.game-mode.guild-war.participation.store') }}" method="POST" class="modal fade" id="modal-participant" tabindex="-1" aria-labelledby="modal-participant" aria-hidden="true" style="display: none;">
    @csrf
    @method('POST')
    <input type="hidden" name="guild_war_id" value="{{ $data->uuid }}" readonly>

    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="h6 modal-title">Add new Participant</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="input-guild_member_id">Available Guild Member</label>
                    <select class="form-control" id="input-guild_member_id" name="guild_member_id" placeholder="Search for Available Guild Member">
                        <option value="">Search for Available Guild Member</option>
                    </select>
                </div>

                <div class="form-group tw__mb-0">
                    <div class="form-check tw__text-sm" id="form-add_more">
                        <input class="form-check-input" type="checkbox" name="create" value="" id="input-add_and_create">
                        <label class="form-check-label" for="input-add_and_create">Add more?</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-link text-gray-600 ms-auto" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-secondary tw__flex tw__items-center tw__gap-1">Submit</button>
            </div>
        </div>
    </div>
</form>
