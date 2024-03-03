<!-- Отображение списка комментариев -->
<div id="comments-list">
    <?php foreach ($comments as $comment): ?>
        <div id="<?= $comment['id']?>" class="comment">
            <p><?= $comment['text'] ?></p>
            <span><?= $comment['name'] ?></span>
            <button class="delete-comment" onclick="deleteComment(<?= $comment['id']?>)"  data-id="<?= $comment['id'] ?>">Удалить</button>
        </div>
    <?php endforeach; ?>
</div>

<div id="paginated_buttons">
    <?php for ($i = 1; $i <= $pages; $i++): ?>
    <button onclick="getCommentsByPage(<?=$i?>)"><?=$i?></button>
    <?php endfor; ?>
</div>

<!-- Форма добавления комментария -->
<form id="comment-form">
    <input type="text" name="name" placeholder="Имя" required>
    <textarea name="text" placeholder="Текст комментария" required></textarea>
    <button type="submit">Добавить комментарий</button>
</form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function(){
        // AJAX запрос на добавление комментария
        $('#comment-form').submit(function(e){
            e.preventDefault();
            $.ajax({
                url: "http://localhost:8084/comments/addComment",
                type: 'post',
                dataType: 'json',
                headers: {'X-Requested-With': 'XMLHttpRequest'},
                data: $(this).serialize(),
                success: function(response){

                    addComment(response[0]);

                    // Обновление списка комментариев
                    $('#comments-list').load(location.href + ' #comments-list>*');
                    $('#comment-form')[0].reset();
                }
            });
        });
    });

function addComment(responseBody)
{
    $('#comments-list').append(`
        <div id="${responseBody['id']}" class="comment">
            <p>${responseBody['text']}</p>
            <span>${responseBody['name']}</span>
            <button class="delete-comment" data-id=${responseBody['id']}">Удалить</button>
        </div>
    `);
}

async function deleteComment(id)
{
    await $.ajax({
        url: 'http://localhost:8084/comments/deleteComment/' + id,
        type: 'post',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(response){
            $(`#${response}`).remove();
        }
    });

}

function getCommentsByPage(page)
{
    $.ajax({
        url: 'http://localhost:8084/comments/page/' + page,
        type: 'get',
        headers: {'X-Requested-With': 'XMLHttpRequest'},
        success: function(response){
            document.body.innerHTML = response;
        }
    })
}

function createPageButtons(pagesCount)
{
    for (let i = 0; i < pagesCount; i++) {
        $(`#paginated_buttons`).append(`<button onclick="getCommentsByPage(${i})">${i}</button>`);
    }
}

</script>

</body>
</html>
