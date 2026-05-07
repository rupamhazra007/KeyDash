<?php
header('Content-Type: application/json; charset=utf-8');
$QUOTES = [
 "The quick brown fox jumps over the lazy dog.",
 "Typing fast is a skill that comes with practice and patience.",
 "Consistency beats intensity when you build lasting habits.",
 "Simplicity is the soul of efficiency.",
 "Programs must be written for people to read and only incidentally for machines to execute.",
 "Practice daily and your fingers will remember the flow of the keys.",
 "Perfection is achieved not when there is nothing more to add but when there is nothing left to take away.",
 "Stay focused, breathe, and keep typing steadily.",
 "Great things are done by a series of small things brought together.",
 "Speed matters, but accuracy wins the long game."
];
echo json_encode(['quote' => $QUOTES[array_rand($QUOTES)]]);
