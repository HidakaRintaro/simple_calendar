<?php

  // 変数の初期化
  $week_list = ['日', '月', '火', '水', '木', '金', '土'];
  $week_cnt = [0, 0, 0, 0, 0, 0, 0];
  $month_ary = [];
  $max_year = 2100;
  $min_year = 1970;

  // 年と月の設定
  if (isset($_POST['submit']) && $_POST['submit'] == 'submit') {
    $year = $_POST['year'];
    $month = $_POST['month'];
  } elseif (isset($_POST['before'])) {
    $get_days = explode('-', $_POST['before']);
    $year = $get_days[0];
    $month = $get_days[1];
    if ($get_days[1] < 1 && $get_days[0] >= $min_year) {
      $year = $get_days[0] - 1;
      $month = 12;
    }
  } elseif (isset($_POST['after'])) {
    $get_days = explode('-', $_POST['after']);
    $year = $get_days[0];
    $month = $get_days[1];
    if ($get_days[1] > 12 && $get_days[0] <= $max_year) {
      $year = $get_days[0] + 1;
      $month = 01;
    }
  } else {
    $year = date('Y');
    $month = date('m');
  }

  // 月の日数
  $week_days = date('t', mktime(0, 0, 0, $month, 1, $year));

  // 月の配列の初期化
  for ($i = 0; $i < floor($week_days / 7); $i++) {
    for ($j = 0; $j < 7; $j++) { 
      $month_ary[$i][$j] = '';
    }
  }

  // 月の配列に日付を代入($i+1が日付を指す)
  for ($i = 0; $i < $week_days; $i++) {
    // 代入日付のタイムスタンプを取得
    $timestamp = mktime(0, 0, 0, $month, ($i + 1), $year);

    // 代入日付の曜日番号を取得
    $week_num = date('w', $timestamp);

    // 月の配列に日付を代入
    $month_ary[$week_cnt[$week_num]][$week_num] = ($i + 1);

    // 曜日ごとの週のカウントアップ
    $week_cnt[$week_num] += 1;

    // 第1週が終わったら1日より前の分のカウントを1にする
    if (max($week_cnt) == 1 && $week_cnt[6] == 1) {
      $week_cnt = [1, 1, 1, 1, 1, 1, 1];
    }
  }

?>
<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <title>万年カレンダー</title>
</head>
<body>
  <form method="post">
    <label for="year">
      <input type="number" name="year" id="year" min="<?php echo $min_year; ?>" max="<?php echo $max_year; ?>" value="<?php echo $year; ?>" required>
    年</label>
    <label for="month">
      <select name="month" id="month">
<?php for ($i = 1; $i <= 12; $i++) : ?>
        <option value="<?php echo $i; ?>" <?php if ($i == $month) echo 'selected'; ?>><?php echo $i; ?></option>
<?php endfor; ?>
      </select>
    月</label>
    <button type="submit" value="submit" name="submit">送信</button>
    <div>
      <button type="submit" value="<?php echo $year.'-'.($month - 1); ?>" name="before"><</button>
      <button type="submit" value="<?php echo $year.'-'.($month + 1); ?>" name="after">></button>
    </div>
  </form>
  <p><?php echo $year; ?>年<?php echo $month; ?>月</p>
  <table>
    <tr>
<?php foreach ($week_list as $val) : ?>
      <th><?php echo $val; ?></th>
<?php endforeach; ?>
    </tr>
<?php foreach ($month_ary as $row) : ?>
    <tr>
<?php   foreach ($row as $val) : ?>
      <td><?php echo $val; ?></td>
<?php   endforeach; ?>
    </tr>
<?php endforeach; ?>

  </table>
</body>
</html>