<div class="modal fade display-none" id="modalWorkLog" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-weight-400" id="exampleModalLabel">Log Work: <span id="issueIdSpan"
                        class="font-weight-600"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="modalWorkLogForm" data-id="">
                    <div class="form-group">
                        <label>JIRA Issue <span class="text-danger fs-12">[required]</span></label>
                        <input type="text" name="jiraIssue" class="form-control jiraIssue font-weight-600"
                            placeholder="PROJ-XXX" required>
                    </div>
                    <div class="form-group">
                        <label>Time Spent <span class="text-danger fs-12">[required]</span></label>
                        <input type="text" name="timeSpent" class="form-control timeSpent font-weight-600 text-success">
                    </div>
                    <div class="form-group">
                        <label>Work Description <span class="text-danger fs-12">[required]</span></label>
                        <textarea class="form-control logDescription fs-14" name="description" rows="3"
                            required></textarea>
                        <span class="text-muted">Please add some description that what you did.</span>
                    </div>
                    <div class="form-group">
                        <label>Add comment on this issue <span class="font-weight-600 text-primary"
                                id="logIssueId"></span></label>
                        <textarea class="form-control" name="comment" rows="3" required></textarea>
                    </div>
                    <div class="form-group logIssueIdField display-none">
                        <label>Add comment on main issue <span class="font-weight-600 text-primary"
                                id="logIssueIdMain"></span></label>
                        <textarea class="form-control" name="commentMain" rows="3" required></textarea>
                        <input type="hidden" class="commentMainIssueId" name="commentMainIssueId">
                    </div>
                    <button type="button" class="btn btn-primary modalWorkLogFormBtn">Submit</button>
                </form>
            </div>
        </div>
    </div>
</div>