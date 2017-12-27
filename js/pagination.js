// pagination_data = [];
// pagination_current_page = [];
// pagination_max_page = [];
// pagination_total_results = [];
// pagination_is_page_load = [];

// pagination_identifiers = [
//                               "recent_posts",
//                               "movies_list",
//                               "movies_list",
//                               "movie_posts_list",
//                          ];

show_more_current_page = [];

show_more_identifiers = [
  "all_posts_list",
  "reviews_list",
  "articles_list",
  "exclusive_articles_list",
];

$(document).ready(function(){
  PaginationInit();

  $('.show_more_btn').click(function() {
    var update_selector = $(this).attr('data-update-selector');
    var pagination_id = $(this).attr('data-pagination-id');
    var content_type = $(this).attr('data-content-type');

    var default_text = $(this).text(); 
    $(this).text("... loading ...");
    $(this).disabled = true;

    var $this = $(this);

    show_more_current_page[pagination_id]++;
    PaginationGetShowMorePage(show_more_current_page[pagination_id],content_type,function(html) {
      if(html) {
        $(html).hide().appendTo(update_selector).fadeIn();
        $this.disabled = false;
        $this.text(default_text);
      } else {
        $this.remove();
      }
    });
  });

});

function PaginationInit() {
     for (var i = 0; i < show_more_identifiers.length; i++) {
          show_more_current_page[show_more_identifiers[i]] = 1;
     }
}


function PaginationGetShowMorePage(page_number,page_type,callback) {
     var result = '';
     $.ajax({
       url: '/pagination/show_more/'+page_type+'/'+page_number,
       type: 'get',
       dataType: 'json',
       async: true,
       success: function(request) {
          if(request.status == 'true') {
            result = request.data;
            callback(request.data);
          } else {
            result = '';
            callback(false);
          }
       }
     });
     return result;
}