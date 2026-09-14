<?php

$conexao = mysqli_connect("localhost", "root", "", "adaltocell");

if (!$conexao) {
    die("Erro ao conectar ao banco de dados: " . mysqli_connect_error());
}