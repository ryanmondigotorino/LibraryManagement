var GlobalSearch = {

    INIT: function(){
        this.EVENTS();
        this.CATEGORY();
    },

    EVENTS: function(){
        $('form.global-search-form').on('submit',function(event){
            event.preventDefault();
            var thisTarget = $(event.target);
            var actionURL = thisTarget.attr('action');
            var searchbarValue = $('input[name="search"]').val();
            var serializeAllData = thisTarget.serialize();
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + serializeAllData);
            $.ajax({
                type: 'get',
                url: actionURL,
                data: serializeAllData,
            }).done(function(){
                location.reload();
            })
        });
    },

    CATEGORY: function(){
        $('p.btn-category').on('click',function(event){
            var thisTarget = $(event.target);
            var dataCategory = thisTarget.attr('data-category');
            var categorySerialize = 'search_category=' + dataCategory;
            history.pushState({}, {}, window.location.origin + window.location.pathname + '?' + categorySerialize);
            location.reload();
        });
    }
};
GlobalSearch.INIT();