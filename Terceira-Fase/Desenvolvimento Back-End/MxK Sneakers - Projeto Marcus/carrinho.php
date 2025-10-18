<?php
session_start();
$conectar = mysql_connect('localhost','root','');
$banco    = mysql_select_db("loja");

$status="";

if (isset($_POST['codigo']) && $_POST['codigo']!=""){
   $codigo = $_POST['codigo'];
   // Removemos a seleção de quantidade. Agora sempre adiciona 1 produto
   
   $resultado = mysql_query("SELECT descricao,preco,foto1 FROM produto WHERE codigo = '$codigo'");
   $row = mysql_fetch_assoc($resultado);
   $descricao = $row['descricao'];
   $preco = $row['preco'];
   $foto1 = $row['foto1'];

   // Adiciona um novo produto por vez
   $cartArray = array($codigo=>array('descricao'=>$descricao,'preco'=>$preco,'foto'=>$foto1));

   if(empty($_SESSION["shopping_cart"])) {
    $_SESSION["shopping_cart"] = $cartArray;
    $status = "<div class='box'>Produto foi adicionado ao carrinho!</div>";
    }
    else{
    $array_keys = array_keys($_SESSION["shopping_cart"]);

    // Ao invés de atualizar a quantidade, simplesmente adicionamos o produto novamente
    // como um novo item no carrinho - cada item terá quantidade 1
    $_SESSION["shopping_cart"] = array_merge($_SESSION["shopping_cart"],$cartArray);
    $status = "<div class='box'>Produto foi adicionado ao carrinho!</div>";
    }
}
?>

<HTML>
<HEAD>
 <TITLE>Carrinho Compras </TITLE>
 <link rel="stylesheet" href="estilo.css">
 
</HEAD>
<BODY>

<?php
if(!empty($_SESSION["shopping_cart"])) {
     $cart_count = count(array_keys($_SESSION["shopping_cart"]));
?>
<div class="cart_div">
<a href="cart.php"><img src="carrinho.png" height=50 width=50/>Carrinho<span>
<?php echo $cart_count; ?></span></a>
</div>
<?php
}

$resultado = mysql_query("SELECT codigo,foto1,descricao,preco FROM produto");
echo "Produtos encontrados: "."<br><br>";
while($row = mysql_fetch_assoc($resultado)){

    echo "<div class='product_wrapper'>
    <form method='post' action=''>
          <input type='hidden' name='codigo' value=".$row['codigo']." />
          <div class='image'><img src='fotos/".$row['foto1']."' height=200 width=200'/></div>
          <div class='name'>".$row['descricao']."</div>
          <div class='price'>$".$row['preco']."</div>
          <!-- Removemos o seletor de quantidade -->
          <button type='submit' class='buy'>COMPRAR</button>
    </form>
    </div>";
}
?>

<div style="clear:both;"></div>

<div class="message_box" style="margin:10px 0px;">
<?php echo $status; ?>
</div>

</BODY>
</HTML>