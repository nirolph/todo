function TaskHandler(settings){
    this.preloader = undefined;
    this.taskList = undefined;
    this.taskTemplate = undefined;
    this.currentPage = undefined;
    this.tasksPerPage = undefined;
    this.currentSorting = undefined;
    
    this.taskDropSelector = undefined;
    this.newTaskSelector = undefined;
    this.taskSelector = undefined;
    this.listTasksSelector = undefined;
    this.taskLogSelector = undefined;
    this.sortSelector = undefined;
    this.pageSelector = undefined;
    this.perPageSelector = undefined;
    this.statusChangeSelector = undefined;
    this.longDescriptionSelector = undefined;
    
    this.checkedTaskClass = undefined;
    
    this.url = {
        'taskList': '/report/tasks',
        'taskLog': '/log/tasks',
        'updateTaskStatus': '/task/update-status',
        'deleteTask': '/task/delete',
        'createTask': '/task/create',
    };
    this.request = {};
    this.response = {};    
    
    this.initSettings(settings);
};

TaskHandler.prototype.initSettings = function(settings){
    this.preloader = settings.preloader || undefined;
    this.taskList = settings.taskList || undefined;
    this.taskTemplate = settings.taskTemplate || undefined;
    this.currentPage = settings.currentPage || 1;
    this.tasksPerPage = settings.tasksPerPage || undefined;
    this.currentSorting = settings.currentSorting || undefined;
    
    this.attachEvents();
};

TaskHandler.prototype.attachEvents = function(){
    
};

TaskHandler.prototype.createTask = function(){
    
};

TaskHandler.prototype.getTasks = function(){
    this.request = {
        'pagination': {
            'tasks_per_page': 3,
            'page': 1
        },
        'sorting': {
            'column': 'dueDate',
            'sort': 'ASC'
        },
    };
    
    $response = this.makeRequest(this.url.taskList);
};

TaskHandler.prototype.getTaskLog = function(){
    this.request = {
        'pagination': {
            'tasks_per_page': '3',
            'page': 1
        },
        'sorting': {
            'column': 'dueDate',
            'sort': 'ASC'
        },
    };
    
    $response = this.makeRequest(this.url.taskLog);
};

TaskHandler.prototype.paginateResults = function(){
    
};

TaskHandler.prototype.sort = function(){
    
};

TaskHandler.prototype.changeTaskStatus = function(){
    this.request = {
        'id': 17,
        'status': 15,
    };
    
    $response = this.makeRequest(this.url.updateTaskStatus);
};

TaskHandler.prototype.viewTaskDetails = function(){
    
};

TaskHandler.prototype.makeRequest = function(url){
    $.ajax({
        method: "POST",
        url: url,
        data: this.request
    }).done(function( response ) {
        console.log(response);
    }).fail(function( response ) {
        console.log('request failed');
    });
};


var handler = new TaskHandler({});
handler.changeTaskStatus();
console.log(handler);