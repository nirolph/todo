function TaskHandler(settings){
    this.currentPage = 1;
    this.tasksPerPage = 5;
    this.sortSwitch = 0;
    this.sortColumn = undefined;
    this.sortOrder = undefined;    
    this.newTaskSelector = undefined;
    this.taskListSelector = undefined;
    this.taskTemplateSelector = undefined;
    this.taskLogSelector = undefined;
    this.sortSelector = undefined;
    this.pageSelector = undefined;
    this.messageBoardSelector = undefined;
    this.messageTemplateSelector = undefined;
    this.taskStatusToggleSelector = undefined;
    this.deleteTaskConfirmationSelector = undefined;
    this.deleteTaskSelector = undefined;
    this.logEntryTemplateSelector = undefined;
    this.deletedTaskModalSelector = undefined;
    this.paginationContainerSelector = undefined;
    this.tasksPerPageSelector = undefined;
    this.tasksPerPageValueSelector = undefined;
    
    this.descriptionInput = undefined;
    this.dueDateInput = undefined;
    this.checkedTaskClass = undefined;
        
    this.sortOrders = {
        0: 'ASC',
        1: 'DESC'
    };
    
    this.url = {
        'taskList': '/report/tasks',
        'taskLog': '/log/tasks',
        'updateTaskStatus': '/task/update-status',
        'deleteTask': '/task/delete',
        'createTask': '/task/create',
    };   
    
    this.initSettings(settings);
};

TaskHandler.prototype.initSettings = function(settings){    
    //selectors
    this.newTaskSelector = settings.newTaskSelector || undefined;
    this.descriptionInput = settings.descriptionInput || undefined;
    this.dueDateInput = settings.dueDateInput || undefined;
    this.messageBoardSelector = settings.messageBoardSelector || undefined;
    this.messageTemplateSelector = settings.messageTemplateSelector || undefined;
    this.taskListSelector = settings.taskListSelector || undefined;
    this.taskTemplateSelector = settings.taskTemplateSelector || undefined;
    this.taskStatusToggleSelector = settings.taskStatusToggleSelector || undefined;
    this.deleteTaskConfirmationSelector = settings.deleteTaskConfirmationSelector || undefined;
    this.deleteTaskSelector = settings.deleteTaskSelector || undefined;
    this.taskLogSelector = settings.taskLogSelector || undefined;
    this.logEntryTemplateSelector = settings.logEntryTemplateSelector || undefined;
    this.deletedTaskModalSelector = settings.deletedTaskModalSelector || undefined;
    this.paginationContainerSelector = settings.paginationContainerSelector || undefined;
    this.pageSelector = settings.pageSelector || undefined;
    this.tasksPerPageSelector = settings.tasksPerPageSelector || undefined;
    this.tasksPerPageValueSelector = settings.tasksPerPageValueSelector || undefined;
    this.sortSelector = settings.sortSelector || undefined;
    this.sortColumn = settings.sortColumn || 'dueDate';
    this.sortOrder = settings.sortOrder || 'DESC';
    
    this.attachEvents();
};

TaskHandler.prototype.attachEvents = function(){
    var _this = this;
    
    //new task event
    this.newTaskSelector.on('click', function(event){
        var dueDate = _this.dueDateInput.val();
        var description = _this.descriptionInput.val();
        
        _this.createTask(dueDate, description);
    });
    
    //toggle task completion event 
    $(document).on('change', this.taskStatusToggleSelector, function(event){
        var checkbox = $("#"+event.target.id);
        if (checkbox.is(':checked')) {
            var id = checkbox.attr('data-task-id');
            _this.changeTaskStatus(id, 0);
        } else {
            var id = checkbox.attr('data-task-id');
            _this.changeTaskStatus(id, 1);
        }
    });
    
    //delete task event
    $(document).on('click', this.deleteTaskSelector, function(event){
        var element = $(event.target).parent();
        var id = element.attr('data-task-id');
        _this.deleteTaskConfirmationSelector.attr('data-task-id', id);
    });
    
    //delete task event
    $(this.deleteTaskConfirmationSelector).on('click', function(event){
        var id = $(this).attr('data-task-id');
        _this.deleteTask(id);
    });
    
    //task description event
    $(document).on('click', '#taskListTable tr td.description', function(event){
        var element = $(this).parent().next();        
        if (element.hasClass('hidden')) {
            element.removeClass('hidden');
        } else {
            element.addClass('hidden');
        }
                
    });
    
    //list task log event
    $(this.taskLogSelector).on('click', function(event){
        _this.getTaskLog();
    });
    
    //pagination event
    $(document).on('click', this.pageSelector, function(event){
        var element = $(event.target);
        
        if (element.hasClass('active')) {
            //if it's the current page, don't do anything
            return false;
        }
        
        var page = element.prop('data-page-nr');
        _this.currentPage = page;
        _this.getTasks();
    });
    
    //tasks per page event
    $(this.tasksPerPageSelector).on('click', function(event){
        var value = $(this).data('amount');
        _this.tasksPerPageValueSelector.html(value);
        _this.tasksPerPage = value;
        _this.getTasks();
    });
    
    //sort event
    $(this.sortSelector).on('click', function(event){
        _this.sortColumn = $(this).data('sort-column');
        _this.sortSwitch = _this.sortSwitch ? 0 : 1;
        _this.sortOrder = _this.sortOrders[_this.sortSwitch];
        _this.getTasks();
    });
};

TaskHandler.prototype.createTask = function(dueDate, description){
    var _this = this;
    var request = {
        'description': description,
        'dueDate' : dueDate
    };
    
    this.makeRequest(this.url.createTask, request, function(response){
        _this.taskCreatedHandler(response);
    });
};

TaskHandler.prototype.taskCreatedHandler = function(response){
    if (response === undefined) {
        console.log('something went terribly wrong');
    } else {
        var alertClass = response.success ? 'alert-success' : 'alert-danger';
        var message = response.messages || [];
        var alertType = response.success ? 'Success!' : 'Error!';
        
        // generate message
        var htmlMessage = this.messageTemplateSelector.clone().html();
        this.messageBoardSelector.html('');

        htmlMessage = htmlMessage.replace('{{messageClass}}', alertClass)
                .replace('{{message}}', message)
                .replace('{{type}}', alertType);
        this.messageBoardSelector.append(htmlMessage);

        // clear inputs
        this.dueDateInput.val('');
        this.descriptionInput.val('');
        
        if (response.success) {
            this.getTasks();
        }
    }
};

TaskHandler.prototype.getTasks = function(){
    var _this = this;
    var request = {
        'pagination': {
            'tasks_per_page': _this.tasksPerPage,
            'page': _this.currentPage
        },
        'sorting': {
            'column': _this.sortColumn,
            'sort': _this.sortOrder
        },
    };
    
    this.makeRequest(this.url.taskList, request, function(response){
        _this.ListTasksHandler(response);
    });
};

TaskHandler.prototype.getTaskLog = function(){
    var _this = this;
    var request = {};
    
    this.makeRequest(this.url.taskLog, request, function(response){
        _this.ListTaskLogHandler(response);
    });
};

TaskHandler.prototype.ListTasksHandler = function(response){
    var _this = this;
    if (response === undefined) {
        console.log('something went terribly wrong');
    } else {
        if (response.success) {
            this.taskListSelector.html('');
            $.each(response.data.tasks, function(index, task){
                var taskTemplate = _this.taskTemplateSelector.clone().html();
                var taskListHtml = taskTemplate.replace(new RegExp('{{statusClass}}', 'g'), task.status ? '' : 'inactive');
                taskListHtml = taskListHtml.replace(new RegExp('{{id}}', 'g'), task.id);
                taskListHtml = taskListHtml.replace(new RegExp('{{shortDescription}}', 'g'), task.description.length > 5 ? task.description.substring(0, 24)+'...' : task.description);
                taskListHtml = taskListHtml.replace(new RegExp('{{dueDate}}', 'g'), task.dueDate);
                taskListHtml = taskListHtml.replace(new RegExp('{{longDescription}}', 'g'), task.description);
                taskListHtml = taskListHtml.replace(new RegExp('{{taskCompletedClass}}', 'g'), task.status > 0 ? '' : 'completed-task');
                taskListHtml = taskListHtml.replace('{{checked}}', task.status > 0 ? '' : 'checked');
                _this.taskListSelector.append(taskListHtml);                 
            });  
            
            //setup page
            var pages = Math.ceil(response.data.details.total / response.data.details.perPage);
            var currentPage = response.data.details.page;
            this.paginationContainerSelector.html('');
            
            for (var index = 1; index <= pages; index++) {
                var active = currentPage === index ? 'active' : '';
                _this.paginationContainerSelector.append(
                    $(document.createElement('div'))
                        .addClass('btn btn-default pageSelector ' + active)
                        .attr('data-page-nr', index)
                        .prop('data-page-nr', index)
                        .html(index)
                );
            };
            //setup showing dropdown
        } else {
            var alertClass = 'alert-danger';
            var message = response.messages || [];
            var alertType = 'Error!';

            // generate message
            var htmlMessage = this.messageTemplateSelector.clone().html();
            this.messageBoardSelector.html('');

            htmlMessage = htmlMessage.replace('{{messageClass}}', alertClass)
                    .replace('{{message}}', message)
                    .replace('{{type}}', alertType);
            this.messageBoardSelector.append(htmlMessage);
        }
    }
};

TaskHandler.prototype.ListTaskLogHandler = function(response){
    console.log(response);
    var _this = this;
    if (response === undefined) {
        console.log('something went terribly wrong');
    } else {
        if (response.success) {
            this.deletedTaskModalSelector.html('');
            $.each(response.data.tasks, function(index, task){
                var taskTemplate = _this.logEntryTemplateSelector.clone().html();
                var taskListHtml = taskTemplate.replace(new RegExp('{{date}}', 'g'), task.deletedDate);
                taskListHtml = taskListHtml.replace(new RegExp('{{description}}', 'g'), task.description);
                _this.deletedTaskModalSelector.append(taskListHtml); 
            });
        } else {
            var alertClass = 'alert-danger';
            var message = response.messages || [];
            var alertType = 'Error!';

            // generate message
            var htmlMessage = this.messageTemplateSelector.clone().html();
            this.messageBoardSelector.html('');

            htmlMessage = htmlMessage.replace('{{messageClass}}', alertClass)
                    .replace('{{message}}', message)
                    .replace('{{type}}', alertType);
            this.messageBoardSelector.append(htmlMessage);
        }
    }
};

TaskHandler.prototype.deleteTask = function(id){
    var _this = this;
    var request = {
        'id': id,
    };
    
    this.makeRequest(this.url.deleteTask, request, function(response){
        _this.taskDeletedHandler(response);
    });
};

TaskHandler.prototype.taskDeletedHandler = function(response){
    // generate message
    var alertClass = response.success ? 'alert-success' : 'alert-danger';
    var message = response.messages || [];
    var alertType = response.success ? 'Success!' : 'Error!';

    // generate message
    var htmlMessage = this.messageTemplateSelector.clone().html();
    this.messageBoardSelector.html('');

    htmlMessage = htmlMessage.replace('{{messageClass}}', alertClass)
            .replace('{{message}}', message)
            .replace('{{type}}', alertType);
    this.messageBoardSelector.append(htmlMessage);

    if (response.success) {
        this.getTasks();
    }
};

TaskHandler.prototype.changeTaskStatus = function(id, status){
    var _this = this;
    var request = {
        'id': id,
        'status': status,
    };
    
    this.makeRequest(this.url.updateTaskStatus, request, function(respone){
        _this.updateStatus(respone, id, status);
    });
};

TaskHandler.prototype.updateStatus = function(response, id, status){
    if (status) {
        $('[data-task-container='+id+']').removeClass('completed-task');
    } else {
        $('[data-task-container='+id+']').addClass('completed-task');
    }
};

TaskHandler.prototype.makeRequest = function(url, request, callback){
    $.ajax({
        method: "POST",
        url: url,
        data: request
    }).done(function( response ) {
        callback(JSON.parse(response));
//        console.log(JSON.parse(response));
    }).fail(function( response ) {
        // generate message
        var alertClass = 'alert-danger';
        var message = 'Could not contact server';
        var alertType = 'Error!';

        // generate message
        var htmlMessage = this.messageTemplateSelector.clone().html();
        this.messageBoardSelector.html('');

        htmlMessage = htmlMessage.replace('{{messageClass}}', alertClass)
                .replace('{{message}}', message)
                .replace('{{type}}', alertType);
        this.messageBoardSelector.append(htmlMessage);

        if (response.success) {
            this.getTasks();
        }
    });
};

$(document).ready(function(){
    var handler = new TaskHandler({
        'newTaskSelector': $('#createNewTask'),
        'dueDateInput'  : $('#newTaskDueDate'),
        'descriptionInput'  : $('#newTaskDescription'),
        'messageBoardSelector': $('#messageBoard'),
        'messageTemplateSelector': $('#messageTemplate'),
        'taskListSelector': $('#taskListTable'),
        'taskTemplateSelector': $('#taskTemplate'),
        'taskStatusToggleSelector': $('#taskStatusToggle'),
        'deleteTaskConfirmationSelector': $('#deleteTaskConfirmation'),
        'deleteTaskSelector': '.deleteTask',
        'taskLogSelector': $('#taskLog'),
        'logEntryTemplateSelector': $('#logEntryTemplate'),
        'deletedTaskModalSelector': $('#taskLogModal .modal-body'),
        'paginationContainerSelector': $('#pagination'),
        'pageSelector': '.pageSelector',
        'tasksPerPageSelector': $('.tasksPerPage'),
        'tasksPerPageValueSelector': $('#tasksPerPageValue'),
        'sortSelector': $('.sort-icon'),
    });

    handler.getTasks();
    $('#newTaskDueDate').datetimepicker({
        minDate: new Date(),
        format: 'DD/MM/YYYY',
    });  
});
