<!-- Add Loan Detail Modal -->
<div class="modal fade" id="addDetailModal" tabindex="-1" aria-labelledby="addDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('loan_details.store') }}">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDetailModalLabel">إضافة خصم جديد</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="loan_header_id" value="{{ $loanHeader->id }}">

                    <!-- Month/Year Input -->
                    <div class="mb-3">
                        <label for="month" class="form-label">الشهر</label>
                        <input type="month" name="month" id="month" class="form-control" required>
                    </div>

                    <!-- Amount -->
                    <div class="mb-3">
                        <label for="amount" class="form-label">المبلغ</label>
                        <input type="number" class="form-control" id="amount" name="amount" required>
                    </div>

                    <!-- Done Checkbox -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="done" name="done">
                        <label class="form-check-label" for="done">تم السداد</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="archived" name="archived">
                        <label class="form-check-label" for="archived">مؤرشف</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    <button type="submit" class="btn btn-primary">إضافة</button>
                </div>
            </div>
        </form>
    </div>
</div>
