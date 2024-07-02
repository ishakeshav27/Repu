<html>

<head>
    <title>RepuNext</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
    <div classname="container">
        <link rel="stylesheet" href="styles.css">
        <h1>Task 1 - Swap two number without using third temp variable</h1>
        <?php
        $var1 = 15;
        $var2 = 20;
        echo "Variables are $var1, $var2 ";
        $var3 = $var1 + $var2;
        $var1 = $var3 - $var1;
        $var2 = $var3 - $var2;
        echo "Variables after swapping are $var1, $var2";
        ?>
        <h1>Task 2 - Get two input from textbox and make operation of addition, sub, div and mul operation using each
            function..</h1>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
            <label for="num1">Number 1:</label>
            <input type="number" id="num1" name="num1" required><br><br>
            <label for="num2">Number 2:</label>
            <input type="number" id="num2" name="num2" required><br><br>
            <input type="submit" name="operation" value="Add">
            <input type="submit" name="operation" value="Subtract">
            <input type="submit" name="operation" value="Multiply">
            <input type="submit" name="operation" value="Divide">
        </form>

        <?php
        function add($num1, $num2)
        {
            return $num1 + $num2;
        }

        function subtract($num1, $num2)
        {
            return $num1 - $num2;
        }

        function multiply($num1, $num2)
        {
            return $num1 * $num2;
        }

        function divide($num1, $num2)
        {
            if ($num2 != 0) {
                return $num1 / $num2;
            } else {
                return "Undefined";
            }
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $num1 = $_POST['num1'];
            $num2 = $_POST['num2'];
            $operation = $_POST['operation'];

            if (is_numeric($num1) && is_numeric($num2)) {
                $num1 = floatval($num1);
                $num2 = floatval($num2);

                switch ($operation) {
                    case "Add":
                        $result = add($num1, $num2);
                        $operationType = "Addition";
                        break;
                    case "Subtract":
                        $result = subtract($num1, $num2);
                        $operationType = "Subtraction";
                        break;
                    case "Multiply":
                        $result = multiply($num1, $num2);
                        $operationType = "Multiplication";
                        break;
                    case "Divide":
                        $result = divide($num1, $num2);
                        $operationType = "Division";
                        break;
                    default:
                        $result = "Invalid Operation";
                        $operationType = "";
                }

                echo "<p>$operationType of $num1 and $num2 is $result</p>";
            } else {
                echo "<p style='color:red;'>Please enter valid numeric values.</p>";
            }
        }
        ?>
    </div>
</body>

</html>