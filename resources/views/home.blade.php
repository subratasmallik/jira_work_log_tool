@extends('layouts.app')
@section('title')
@endsection
@section('content')
<div class="fixedTop">
    <div class="bg-default border-bottom">
        <form method="POST" id="formSearchIssue" action="{{url('/searchIssue')}}">
            @csrf
            <table class="table border-none vm border-bottom">
                <tr>
                    <td class="text-left font-weight-500">Welcome, <span
                            class="font-weight-500"><?= Session::get('username') ?></span></td>
                    <td class="w100 text-right">
                        <a href="{{url('/logout')}}" class="btn btn-danger btn-sm font-weight-500">Logout <i
                                class="fas fa-sign-out-alt"></i></a>
                    </td>
                </tr>
            </table>
            <table class="table border-none vm">
                <tr>
                    <td>
                        <input type="text" name="jql" value='<?= $response['postData']['jql']; ?>'
                            class="form-control rounded-0 bg-default fs-13">
                    </td>
                    <td class="text-right w75"><button type="submit" class="btn btn-primary"><i
                                class="fas fa-search"></i> Search</button></td>
                </tr>
            </table>
        </form>
    </div>
    <div class="">
        <div class="table vm bg-light">
            <div class="table-cell">
                <form method="POST" id="addIssueForm">
                    @csrf
                    <table class="table border-none">
                        <tr>
                            <td class="text-left">
                                <input type="text" name="issyeKey" required value=""
                                    class="form-control display-inline-block w125 rounded-0" placeholder="PRO-XXX">
                                <button type="submit" class="btn btn-success">Add</button>
                            </td>
                            <td class="text-right pr-1 w100">
                                <button type="button" class="btn btn-warning font-weight-500 unKnwonIssue"><i
                                        class="fas fa-plus"></i> Unknown</button>
                            </td>
                        </tr>
                    </table>
                </form>
            </div>
        </div>
        <table class="table border-none vm searchFromList">
            <tr>
                <td>
                    <i class="fas fa-search position-absolute"></i>
                    <input type="text" class="form-control display-inline-block rounded-0" value="" id="searchInput"
                        placeholder="Search issue from the list..">
                </td>
            </tr>
        </table>
    </div>
</div>
<div class="pl-1 pr-1 listJiraIssueWrapper">
    @if(isset($response['jiraIssues']) && count($response['jiraIssues']['issues'])>0)
    @foreach($response['jiraIssues']['issues'] as $jiraIssue)
    @if(isset($jiraIssue['fields']['subtasks']) && count($jiraIssue['fields']['subtasks'])>0)
    @else
    <div class="bg-light mb-3 ml-2 mr-2 border-all box-shadow rounded rowIssue" data-issue="<?= $jiraIssue['key'] ?>"
        id="<?= $jiraIssue['key'] ?>" data-issueName="<?= $jiraIssue['fields']['summary'] ?>">
        @if(isset($jiraIssue['fields']['parent']))
        <p class="fs-12 text-default mt-1 mb-0  pa-1 pl-2 pr-2rounded parentIssue"
            data-parentIssue="<?= $jiraIssue['fields']['parent']['key']; ?>">
            <a target="_blank"
                class="text-default fs-12 font-weight-500"><?= $jiraIssue['fields']['parent']['key']; ?></a>
            <span class=""><?= $jiraIssue['fields']['parent']['fields']['summary']; ?></span>
            <span class="font-weight-600"> [<span><?= $jiraIssue['fields']['status']['name'] ?></span>] /
                <?= $jiraIssue['key'] ?></span>
        </p>
        @endIf
        <div class="bg-white pa-1 pl-2 pr-2 rounded">
            <p class="mb-1 font-weight-600">
                <span class=" fs-14"><?= $jiraIssue['key'] ?>:</span>
                <span
                    class="text-center fs-13 pl-1 pr-1  font-weight-500 <?= $jiraIssue['fields']['status']['name']; ?>">
                    [<?= $jiraIssue['fields']['status']['name'] ?>]
                </span>
                <span class="fs-14"><?= $jiraIssue['fields']['summary'] ?></span>
            </p>
            <p class="mb-0 fs-12 font-weight-500 text-info">
                <span class="text-default">Remaining time:</span>
                <?= \App\Http\Controllers\UtilityController::secondToHrMS($jiraIssue['fields']['timeestimate']); ?>
            </p>
        </div>
        <table class="table border-none vm">
            <tr>
                <td class="w105"><input type="text" name="timer"
                        class="form-control timer fs-17 text-center font-weight-600 rounded-0" readonly placeholder="0"
                        value="00:00:00"></td>
                <td class="text-left btnAction mainBtns">
                    <button class="btn btn-success btnStart"><i class="fas fa-play"></i></button>
                    <button class="btn btn-info btnPause hide"><i class="fas fa-pause"></i></button>
                    <button class="btn btn-warning btnResume hide"><i class="fas fa-play"></i></button>
                    <button class="btn btn-primary btnReset hide"><i class="fas fa-sync-alt"></i></button>
                    <button class="btn btn-danger btnStop hide"><i class="far fa-stop-circle"></i></button>
                </td>
                <td class="text-danger w50 text-center deleteIssue">
                    <button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>
                </td>
            </tr>
        </table>
    </div>
    @endIf
    @endForeach
    @else
    <div class="text-center fs-20 pa-5">No record found!</div>
    @endif
</div>

<!--Modal to work log-->
@include('modal/workLog')

@endsection

@section('script')
<script src='https://cdnjs.cloudflare.com/ajax/libs/timer.jquery/0.6.5/timer.jquery.min.js'></script>
<script>
    var $mainBtns = '<button class="btn btn-success btnStart"><i class="fas fa-play"></i></button>'+
                    '<button class="btn btn-info btnPause hide"><i class="fas fa-pause"></i></button>'+
                    '<button class="btn btn-warning btnResume hide"><i class="fas fa-play"></i></button>'+
                    '<button class="btn btn-primary btnReset hide"><i class="fas fa-sync-alt"></i></button>'+
                    '<button class="btn btn-danger btnStop hide"><i class="far fa-stop-circle"></i></button>';
    $(document).ready(function () {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        $(document).on('click', '.btnStart', function () {
            var $rowIssue = $(this).closest('.rowIssue');
            $('.btnAction > .btnPause').trigger('click');
            var $tr = $(this).closest('tr');
            var $this = $(this);
            $tr.find('.timer').timer({
                format: '%H:%M:%S'
            });
            $tr.find('.timer').timer('start');
            $this.addClass('hide');
            $tr.find('.btnPause, .btnReset ,.btnStop').removeClass('hide');
            $('.rowIssue').removeClass('timerOn');
            $rowIssue.addClass('timerOn');
        });
        $(document).on('click', '.btnPause', function () {
            var lastClass = $(this).attr('class').split(' ').pop();
            if (lastClass !== 'hide') {
                var $tr = $(this).closest('tr');
                var $this = $(this);
                $(this).closest('tr').find('.timer').timer('pause');
                $this.addClass('hide');
                $tr.find('.btnResume').removeClass('hide');
            }
        });
        $(document).on('click', '.btnResume', function () {
            var $rowIssue = $(this).closest('.rowIssue');
            $('.btnAction > .btnPause').trigger('click');
            var $tr = $(this).closest('tr');
            var $this = $(this);
            $(this).closest('tr').find('.timer').timer('resume');
            $this.addClass('hide');
            $tr.find('.btnPause').removeClass('hide');
            $('.rowIssue').removeClass('timerOn');
            $rowIssue.addClass('timerOn');
        });
        $(document).on('click', '.btnReset', function () {
            var $rowIssue = $(this).closest('.rowIssue');
            $.confirm({
                title: 'Confirm!',
                content: 'Are you sure to reset this time?',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-default',
                        action: function () {

                        }
                    },
                    confirm: {
                        text: 'Confirm',
                        btnClass: 'btn-success',
                        action: function () {
                            resetTimer($rowIssue);
                            $rowIssue.removeClass('timerOn');
                        }
                    }
                }
            });
        });
        $(document).on('click', '.btnStop', function () {
            var $tr = $(this).closest('.rowIssue');
            var $this = $(this);
            $tr.find('.btnPause').trigger('click');
            // open modalWorkLog modal
            $('#modalWorkLog #issueIdSpan').html($tr.attr('data-issue'));
            $('#modalWorkLog #logIssueId').html($tr.attr('data-issue'));
            $('#modalWorkLog form').attr('data-id', $tr.attr('id'));
            $('#modalWorkLog .jiraIssue').val($tr.attr('data-issue'));
            //parentIssue
            var parentIssueId=$tr.find('.parentIssue').attr('data-parentIssue');
            if(parentIssueId !==undefined){
                $('#modalWorkLog').find('.logIssueIdField').removeClass('display-none');
                $('#modalWorkLog').find('#logIssueIdMain').html(parentIssueId);
                $('#modalWorkLog').find('.commentMainIssueId').val(parentIssueId);
            }else{
                $('#modalWorkLog').find('.logIssueIdField').addClass('display-none');
                $('#modalWorkLog').find('.commentMainIssueId').val('');
            }
            //if($tr.find('.parentIssue').attr('data-parentIssue'))
            $('#modalWorkLog .timeSpent').val($(this).closest('tr').find(".timer").val());
            $('#modalWorkLog .logDescription').val('');

            $('#modalWorkLog').find('textarea[name="comment"]').val('');
            $('#modalWorkLog').find('textarea[name="commentMain"]').val('');

            if ($tr.find('.issueDescription').val() !== undefined) {
                $('#modalWorkLog .logDescription').val($tr.find('.issueDescription').val());
            }else{
                $('#modalWorkLog .logDescription').val($tr.attr('data-issueName'));
            }
            $('#modalWorkLog').modal('show');
        });
        $(document).on('click', '.modalWorkLogFormBtn', function () {
            var $this = $(this);
            if ($('#modalWorkLogForm').find('input[name="jiraIssue"]').val() === '') {
                $.alert({
                    title: 'Alert!',
                    content: "<b>JIRA Issue</b> shouldn't be blank"
                });
                return false;
            }
            if ($('#modalWorkLogForm').find('textarea[name="description"]').val() === '') {
                $.alert({
                    title: 'Alert!',
                    content: "Please add some <b>Work Description</b> for this log."
                });
                $('#modalWorkLogForm').find('textarea[name="description"]').focus();
                return false;
            }
            var $url = "{{url('log-work')}}?_token=" + csrf_token + "";
            pageLoaderOn();
            $this.html('<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Please wait');
            $.ajax({
                url: $url,
                type: "POST",
                data: {logData: $('#modalWorkLogForm').serializeArray()},
                success: function ($response) {
                    if ($response.status === true) {
                        //$('#' + $('#modalWorkLog form').attr('data-id')).find('.btnReset').trigger('click');
                        resetTimer($('#' + $('#modalWorkLog form').attr('data-id')));
                        $('#modalWorkLog').modal('hide');
                        $.alert({
                            title: 'Successful!',
                            content: 'Work log has been done successfully!',
                        });
                    } else {
                        $.alert({
                            title: 'Alert!',
                            content: $response.error.errorMessages,
                        });
                    }
                    pageLoaderOff();
                    $this.html('Submit');
                },
                error: function ($response) {
                    console.log($response);
                    pageLoaderOff();
                    $this.html('Submit');
                }
            });
            return false;
        });
        $(document).on('click', '.deleteIssue button', function () {
            var $tr = $(this).closest('.rowIssue');
            $.confirm({
                title: 'Confirm!',
                content: '<p>Jira Issue Id: <b>#' + $tr.attr('data-issue') + '</b></p>Are you sure to delete this issue?',
                buttons: {
                    cancel: {
                        text: 'Cancel',
                        btnClass: 'btn-default',
                        action: function () {

                        }
                    },
                    confirm: {
                        text: 'Confirm',
                        btnClass: 'btn-success',
                        action: function () {
                            $tr.remove();
                        }
                    }
                }
            });
        });
        $(document).on('click', '.unKnwonIssue', function () {
            unKnwonIssue($(this));
        });
        // Search 
        $('#formSearchIssue').submit(function () {
            var startedIssue = $('.listJiraIssueWrapper').find('.btnStart.hide').length;
            if (startedIssue > 0) {
                pageLoaderOff();
                $.confirm({
                    title: 'Alert - Work log pending!',
                    content: '<p><b>' + startedIssue + '</b> issue is pending to work log</p> <p>Please complete your work log OR add issue manually by issue key.</p>',
                    buttons: {
                        cancel: {
                            text: 'OK',
                            btnClass: 'btn-default'
                        }
                    }
                });
                return false;
            }
        });
        // Add issue manually

        $('#addIssueForm').submit(function () {
            var $this = $(this);
            var $url = "{{url('issueDetails')}}?_token=" + csrf_token + "";
            $.ajax({
                url: $url,
                type: "POST",
                data: {formData: $(this).serializeArray()},
                success: function ($response) {
                    if ($response.data === null) {
                        $.alert({
                            title: 'Alert!',
                            content: 'Please enter a issue key!',
                        });
                    }
                    pageLoaderOff();
                    addIssueRow($response, $this);
                },
                error: function ($response) {
                    pageLoaderOff();
                    console.log($response);
                }
            });
            return false;
        });
        // search / filter
        $("#searchInput").on("keyup", function () {
            var value = $(this).val().toLowerCase();
            $(".listJiraIssueWrapper .rowIssue").filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
        });
    });
    function unKnwonIssue($this) {
        var $rowId = randomNumber();
        var $htmlData = '<div class="bg-light mb-3 ml-2 mr-2 border-all box-shadow rounded rowIssue" ' +
                'id="' + $rowId + '">' +
                '<p class="pa-1 mb-0"><textarea type="text" class="form-control issueDescription" placeholder="Working for?" rows="2"></textarea></p>' +
                '<table class="table border-none vm">' +
                '<tr>' +
                '<td class="w105"><input type="text" name="timer" class="form-control timer fs-17 text-center font-weight-600 rounded-0" readonly placeholder="0" value="00:00:00"></td>' +
                '<td class="text-left btnAction">' + $mainBtns + '</td>' +
                '<td class="text-danger w50 text-center deleteIssue">' +
                '<button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>' +
                '</td>' +
                '</tr>' +
                '</table></div>';
        $('.listJiraIssueWrapper').prepend($htmlData);
        $('#' + $rowId + ' .issueDescription').focus();
        $('#' + $rowId + ' .btnStart').trigger('click');

    }
    function resetTimer($tr) {
        $tr.find('.timer').timer('remove');
        $tr.find('.btnAction .btn').addClass('hide');
        $tr.find('.btnStart').removeClass('hide');
        $tr.find('.timer').val('00:00:00');
    }
    function secondsTimeSpanToHMS(s) {
        var h = Math.floor(s / 3600); //Get whole hours
        s -= h * 3600;
        var m = Math.floor(s / 60); //Get remaining minutes
        s -= m * 60;
        return h + ":" + (m < 10 ? '0' + m : m) + ":" + (s < 10 ? '0' + s : s); //zero padding on minutes and seconds
    }
    function addIssueRow($response, $this) {
        console.log($response);
        if ($response.error) {
            $.alert({
                title: 'Alert!',
                content: $response.error.errorMessages,
            });
            return false;
        }
        // check exist or not
        if ($('.listJiraIssueWrapper .rowIssue[id="' + $response.data.key + '"]').length > 0) {
            $.alert({
                title: 'Alert!',
                content: $response.data.key + ' already added!',
            });
            $('#searchInput').val($response.data.key);
            $('#searchInput').keyup();
            return false;
        }

        var $parentData = '';
        if ($response.data.fields.parent !== undefined) {
            $parentData = '<p class="fs-12 text-default mt-1 mb-0  pa-1 pl-2 pr-2rounded">' +
                    '<a  target="_blank" class="text-default fs-12 font-weight-500" >' + $response.data.fields.parent.key + '</a> ' +
                    '<span class="">' + $response.data.fields.parent.fields.summary + '</span>' +
                    '<span class="font-weight-600"> [<span>' + $response.data.fields.parent.fields.status.name + '</span>] / ' + $response.data.key + '</span>' +
                    '</p>';
        }
        var $htmlData = '' + $parentData + ' ' +
                '<div class="bg-white pa-1 pl-2 pr-2 rounded">' +
                '<p class="mb-1 font-weight-600">' +
                '<span class=" fs-14">' + $response.data.key + ':</span>' +
                '<span class="text-center fs-13 pl-1 pr-1  font-weight-500 ' + $response.data.fields.status.name + '">' +
                '[' + $response.data.fields.status.name + ' ]' +
                '</span>' +
                '<span class="fs-14">' + $response.data.fields.summary + '</span>' +
                '</p>' +
                '<p class="mb-0 fs-12 font-weight-500 text-info">' +
                '<span class="text-default">Remaining time:</span> ' + secondsTimeSpanToHMS($response.data.fields.timeestimate) + ' ' +
                '</p>' +
                '</div>';
        var $finalHtml = '<div class="bg-light mb-3 ml-2 mr-2 border-all box-shadow rounded rowIssue" ' +
                'data-issue="' + $response.data.key + '" ' +
                'id="' + $response.data.key + '"' +
                '>' + $htmlData + '' +
                '<table class="table border-none vm">' +
                '<tr>' +
                '<td class="w105"><input type="text" name="timer" class="form-control timer fs-17 text-center font-weight-600 rounded-0" readonly placeholder="0" value="00:00:00"></td>' +
                '<td class="text-left btnAction">' + $mainBtns + '</td>' +
                '<td class="text-danger w50 text-center deleteIssue">' +
                '<button class="btn btn-danger"><i class="far fa-trash-alt"></i></button>' +
                '</td>' +
                '</tr>' +
                '</table></div>';
        $('.listJiraIssueWrapper').prepend($finalHtml);
        $('#' + $response.data.key + ' .btnStart').trigger('click');
    }
</script>
@endsection