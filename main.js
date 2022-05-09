'use strict'
$(document).ready(function () {
    $("#sidebar").mCustomScrollbar({
        theme: "minimal"
    });

    $('#dismiss, .overlay').on('click', function () {
        $('#sidebar').removeClass('active');
        $('.overlay').removeClass('active');
    });

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').addClass('active');
        $('.overlay').addClass('active');
        $('.collapse.in').toggleClass('in');
        $('a[aria-expanded=true]').attr('aria-expanded', 'false');
    });

    var tmp="";
    /* search list task */
    $("#search").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#list .hnotice"+tmp+"").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    var d="";
    /* search table dayoff duyet*/
    $("#search_duyet").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("tbody>"+d+"").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    $("#tatca").on('click',()=>{
        d="";
        $("#search_duyet").val("");
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#tatca').addClass('hsidebar-filter-active');
        $('tr').show();
    })
    $("#dongy").on('click',()=>{
        d=".approved";
        $("#search_duyet").val("");
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#dongy').addClass('hsidebar-filter-active');
        $('tbody>tr').hide();
        $('.approved').show();

    })
    $("#tuchoi").on('click',()=>{
        d=".refused";
        $("#search_duyet").val("");        
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#tuchoi').addClass('hsidebar-filter-active');
        $('tbody>tr').hide();
        $('.refused').show();

    })
    $("#dangdoi").on('click',()=>{
        d=".waiting";
        $("#search_duyet").val("");       
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#dangdoi').addClass('hsidebar-filter-active');
        $('tbody>tr').hide();
        $('.waiting').show();

    })
    /* search table dayoff */
    $("#search_dayoff").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("table > tbody > tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    //deadline
    deadline.min = new Date().toISOString().split("T")[0];

    //filter-left
    $('#all').on('click',()=>{
        tmp="";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#all').addClass('hsidebar-filter-active');
        $('.hnotice').show();
    })
    $('#success').on('click',()=>{
        tmp="-success";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#success').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-success').show();
    })
    $('#new').on('click',()=>{
        tmp="-new";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#new').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-new').show();
    })
    $('#rejected').on('click',()=>{
        tmp="-rejected";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#rejected').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-rejected').show();
    })
    $('#waiting').on('click',()=>{
        tmp="-waiting";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#waiting').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-waiting').show();
    })
    $('#inprogress').on('click',()=>{
        tmp="-inprogress";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#inprogress').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-inprogress').show();
    })
    $('#cancel').on('click',()=>{
        tmp="-cancel";
        $("#search").val(""); 
        $('.hsidebar-filter').removeClass('hsidebar-filter-active');
        $('#cancel').addClass('hsidebar-filter-active');
        $('.hnotice').hide();
        $('.hnotice-cancel').show();
    })

    //
   

});

function showPassword() {
    let x = document.getElementById("password");
    let icon = document.getElementById("eye-icon")
    if (x.type === "password") {
        x.type = "text";
        icon.classList.remove("fa-eye")
        icon.classList.add("fa-eye-slash")
    } else {
        x.type = "password";
        icon.classList.remove("fa-eye-slash")
        icon.classList.add("fa-eye")
    }
}

function showPassword2() {
    let x = document.getElementById("password2");
    let icon = document.getElementById("eye-icon2")
    if (x.type === "password") {
        x.type = "text";
        icon.classList.remove("fa-eye")
        icon.classList.add("fa-eye-slash")
    } else {
        x.type = "password";
        icon.classList.remove("fa-eye-slash")
        icon.classList.add("fa-eye")
    }
}

//validate
(function () {  
    'use strict';  
    window.addEventListener('load', function () {
        var form = document.getElementById('form-add-task');  
        form.addEventListener('submit', function (event) {  
            if (form.checkValidity() === false) {  
                event.preventDefault();  
                event.stopPropagation();  
            }  
            form.classList.add('was-validated');  
        }, false);  
    }, false);  
})();

//list file
function updateList() {
    var size=0;
    var input = document.getElementById('filepost');
    var output = document.getElementById('fileList');
    var children = "";
    for (var i = 0; i < input.files.length; ++i) {
        size += input.files.item(i).size;
        children += '<li>' + input.files.item(i).name + '</li>';
    }
    if(size>209715200){/*200MB */
        output.innerHTML = 'Tổng file vượt quá giới hạn';
        input.value="";
    }else{
        output.innerHTML = '<ul>'+children+'</ul>';
    }
}

//download file
function download(name){
    window.location="download.php?path=files/"+name;
 }

$(document).ready(function() {
    $('#data-table').DataTable({
        paging: true
    });
});

//hide alert
$('.alert').delay(3000).fadeOut('slow');

//Chống dubble click;
$("#btn_tao").click(function(){
    $("#btn_tao").hide();
    $("#btn_tao").delay(500).fadeIn(500);
});

//modal confirm truong phong
$(document).ready(function(){
    $("#confirmTruongPhong").modal('show');
});


// To style only selects with the my-select class
$('.my-select').selectpicker();


