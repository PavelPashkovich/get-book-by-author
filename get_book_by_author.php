<?php
function get_coauthored_books($author_name) {
    $mysql = mysqli_connect("207.180.216.166", "user1", "qwerty12345", 'pavel_pashkovich');
    mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

    $sql_author = 'select * from authors where name = "'.$author_name.'"';
    $author = mysqli_query($mysql, $sql_author);
    $author_ids = [];
    while ($row = $author->fetch_array()){
        $author_ids[]=$row['id'];
    }
    $author_id = implode('', $author_ids);

    $sql_books_by_author = 'select * from books_authors where author_id in ('.$author_id.')';
    $books = mysqli_query($mysql, $sql_books_by_author);
    $books_ids = [];
    while($row = $books->fetch_array()){
        $books_ids[] = $row['book_id'];
    }
    $book_id = implode(',', $books_ids);

    $sql_books = 'select book_id, COUNT(*) as count FROM books_authors where book_id in ('.$book_id.') GROUP BY book_id';
    $result = mysqli_query($mysql, $sql_books);
    $book_id_needed = [];
    while($row = $result->fetch_array()){
        if($row['count'] > 1) {
            $book_id_needed[] = $row['book_id'];
        }
    }
    $book = implode(',', $book_id_needed);

    $sql_book = 'select * from books where id in ('.$book.')';
    $books_name = mysqli_query($mysql, $sql_book);
    $books_found = [];
    while($row = $books_name->fetch_array()){
        $books_found[] = $row['name'];
    }
    $book_name = implode("<br>", $books_found);
    print_r($book_name."<br>");
}

get_coauthored_books('Андрей Жвалевский');
get_coauthored_books('Евгения Пастернак');
get_coauthored_books('Стивен Кинг');
get_coauthored_books('Илья Ильф');


