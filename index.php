<?php
require_once "Game.php";
require_once "Bet.php";
?>
<html>
<head>
    <link rel="stylesheet" href="index.css">
</head>
<body>
<table>
    <tr>
        <td>Выходные данные</td>
        <td>Правильный ответ</td>
    </tr>
    <?php
    for ($i = 1; $i < 9; $i++):
        $ansName = "00" . $i . ".ans";
        $datName = "00" . $i . ".dat";
        $ans =  htmlentities(file_get_contents("A\\" . $ansName));
        $dat =  htmlentities(file_get_contents("A\\" . $datName));

        $ans = str_replace("\n", "<br>", $ans);
        $dat = str_replace("\n", "<br>", $dat);

        $lines = file("A\\" . $datName, FILE_IGNORE_NEW_LINES);

        $capital = 0;

        $array = array();

        for ($j = 1; $j <= $lines[0]; $j++) {
            $line = explode(" ", $lines[$j]);

            $bet = new Bet();
            $bet->game_id = $line[0];
            $bet->sum = $line[1];
            $bet->prediction = $line[2];


            $capital += $bet->sum;

            $array += [$bet->game_id => $bet];

        }



        $bank = 0;

        for ($j = 0; $j < $lines[$lines[0]+1]; $j++) {

            $game = new Game();
            $line = explode(" ", $lines[$lines[0] + 2 + $j]);


            $game->id = $line[0];
            $game->lose = $line[1];
            $game->win = $line[2];
            $game->draw = $line[3];
            $game->result = $line[4];

            if (isset($array[$game->id])) {
                if ($game->result == $array[$game->id]->prediction) {
                    $bank += match ($game->result) {
                        "L" => $array[$game->id]->sum * $game->lose,
                        "R" => $array[$game->id]->sum * $game->win,
                        "D" => $array[$game->id]->sum * $game->draw,
                    };
                }
            }
        }

        $result = $bank - $capital;

        ?>
        <tr>
            <td><?=$ans?></td>
            <td><?=$result?></td>
        </tr>
    <?php endfor;
    ?>
</table>
<table>
    <tr>
        <td>Входные данные</td>
        <td>Выходные данные</td>
    </tr>
    <?php
        for ($i = 1; $i < 9; $i++):
        $ansName = "00" . $i . ".ans";
        $datName = "00" . $i . ".dat";
        $ans =  htmlentities(file_get_contents("A\\" . $ansName));
        $dat =  htmlentities(file_get_contents("A\\" . $datName));

        $ans = str_replace("\n", "<br>", $ans);
        $dat = str_replace("\n", "<br>", $dat);
    ?>
        <tr>
            <td><?=$dat?></td>
            <td><?=$ans?></td>
        </tr>
    <?php endfor;
    ?>
</table>
</body>
</html>