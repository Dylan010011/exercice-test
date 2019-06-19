$('#add-image').click(function() {

    //Recupere num futur champs
    const index = +$('#test').val();
        
    const add = $('#ad_images').data('prototype').replace(/__name__/g, index);

    $('#ad_images').append(add);

    $('#test').val(index + 1);

    handleDeleteButtons();
    
});

function handleDeleteButtons() {

    $('button[data-action="delete"]').click(function() {

        const target = this.dataset.target;
        $(target).remove();

    });
}

function updateCounter() {

    const count = +$('#ad_images div.form-group').length;
    
    $('#test').val(count);
    
}

updateCounter();

handleDeleteButtons();