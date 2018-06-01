<?php
require_once "vendor/autoload.php";

$upcomingTasks = getUpcomingTasks($link);
$result = [];

foreach($upcomingTasks as $block) {
    if(array_key_exists($block["user_email"], $result)) {
        $result[$block["user_email"]]["task_name"] .= ', '.$block["task_name"];
        $result[$block["user_email"]]["term_date"] .= ', '.$block["term_date"];
        $result[$block["user_email"]]["user_name"] = $block["user_name"];
    } else {
        $result[$block["user_email"]] = $block;
    }
}

if (count($result) > 0) {
    $transport = (new Swift_SmtpTransport('phpdemo.ru', 25))
    ->setUsername('keks@phpdemo.ru')
    ->setPassword('htmlacademy');

    foreach ($result as $i => $item) {
        $message = new Swift_Message("Уведомление от сервиса «Дела в порядке»");
        $message->setTo([$item["user_email"] => $item["user_name"]]);
        $message->setBody("Уважаемый(ая)," . $item["user_name"] .
        ". У вас запланирована(ы) задача(и) " .  $item["task_name"] . " на " . $item["term_date"], "text/plain");
        $message->setFrom("keks@phpdemo.ru", "Дела в порядке");

        $mailer = new Swift_Mailer($transport);
        $mailer->send($message);
    }
}
