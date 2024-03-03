$(document).ready(function () {
    // AJAX запрос на добавление комментария
    $('#comment-form').submit(function (e) {
        e.preventDefault();
        $.ajax({
            url: "http://localhost:8084/comments/addComment",
            type: 'post',
            dataType: 'json',
            headers: {'X-Requested-With': 'XMLHttpRequest'},
            data: $(this).serialize(),
            success: function (response) {

                addComment(response[0]);

                // Обновление списка комментариев
                $('#comments-list').load(location.href + ' #comments-list>*');
                $('#comment-form')[0].reset();
            }
        });
    });
});

function addComment(responseBody) {
    $('#comments-list').append(`
        <div id="${responseBody['id']}" class="comment">
            <p>${responseBody['text']}</p>
            <span>${responseBody['name']}</span>
            <button class="delete-comment" data-id=${responseBody['id']}">Удалить</button>
        </div>
    `);
}

async function deleteComment(id) {
    await $.ajax({
        url: 'http://localhost:8084/comments/deleteComment/' + id,
        type: 'post',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function (response) {
            $(`#${response}`).remove();
        }
    });

}

function getCommentsByPage(page) {
    $.ajax({
        url: 'http://localhost:8084/comments/page/' + page,
        type: 'get',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function (response) {
            document.body.innerHTML = response;
        }
    })
}

function createPageButtons(pagesCount) {
    for (let i = 0; i < pagesCount; i++) {
        $(`#paginated_buttons`).append(`<button onclick="getCommentsByPage(${i})">${i}</button>`);
    }
}