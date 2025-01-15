<!-- Edit Loan Detail Modal -->
<div class="modal fade" id="editDetailModal-{{ $detail->id }}" tabindex="-1" aria-labelledby="editDetailModalLabel-{{ $detail->id }}" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('loan_details.update', $detail->id) }}">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editDetailModalLabel-{{ $detail->id }}">تعديل الخصم</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- Month/Year Input (pre-filled with value) -->
                    <div class="mb-3">
                        <label for="start_date-{{ $detail->id }}" class="form-label">الشهر</label>
                        <input type="month" name="month" id="month-{{ $detail->id }}" class="form-control" value="{{ sprintf('%04d-%02d', $detail->year, $detail->month) }}" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount-{{ $detail->id }}" class="form-label">المبلغ</label>
                        <input type="number" class="form-control" id="amount-{{ $detail->id }}" name="amount" value="{{ $detail->amount }}" required>
                    </div>

                    <!-- Done Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="done-{{ $detail->id }}" name="done" {{ $detail->done ? 'checked' : '' }}>
                        <label class="form-check-label" for="done-{{ $detail->id }}">تم السداد</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="archived-{{ $detail->id }}" name="archived" {{ $detail->archived ? 'checked' : '' }}>
                        <label class="form-check-label" for="archived-{{ $detail->id }}">مؤرشف</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">تعديل</button>
                </div>
            </div>
        </form>
    </div>
</div>
