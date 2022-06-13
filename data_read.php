<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <title>antibiotics_read</title>
</head>

<body>
    <?php
    $array = array();

    // ファイルを開く（読み取り専用）
    $file = fopen('./data/antibiotics_data.csv', 'r');
    // ファイルをロック
    flock($file, LOCK_EX);

    if ($file) {
        while ($line = fgetcsv($file)) {
            array_push($array, $line);
        }
    }

    // ロックを解除する
    flock($file, LOCK_UN);
    // ファイルを閉じる
    fclose($file);

    ?>

    <div id="whole">
        <h1>Antibio</h1>
        <div id="selectBox">
            <select class="form-select" aria-label="antibiotics" id="antibiotics">
            </select>
        </div>
        <div id="resultBox">
            <table class="table">
                <thead class="table-info">
                    <tr>
                        <th scope="col">ccr</th>
                        <th scope="col">dose</th>
                        <th scope="col">interval</th>
                    </tr>
                </thead>
                <tbody id="ResultData">
                </tbody>
            </table>
        </div>
        <p>※サンフォードガイド2021参照</p>
    </div>

    <script>
        //phpのjsonコードをオブジェクトに変換
        const array = <?= json_encode($array) ?>;
        const objs = array.map((x, i) => ({
            key: x[0],
            antibiotics: x[1],
            ccr: x[2],
            dose: x[3],
            intervel: x[4]
        }));
        objs.shift(); //カラム名を除去

        //セレクトボタンに入れるantibiotics_nameを抽出
        const antibiotics_name_array = objs.map(x => x.antibiotics);
        const setAntibioticsNameArray = [...new Set(antibiotics_name_array)];

        //セレクトオプションの生成
        let outputText = "<option selected>select antibiotics</option>";
        setAntibioticsNameArray.map((x) => {
            outputText += `<option value="${x}">${x}</option>`
        });
        const antibiotics = document.getElementById('antibiotics');
        antibiotics.innerHTML = outputText;

        //onchangeイベント
        antibiotics.onchange = () => {
            //filting by selected value
            const SetAntibiotics = antibiotics.value;
            const SetAntibioticsObj = objs.filter(x => x.antibiotics == SetAntibiotics);
            console.log(SetAntibioticsObj);

            //create result dom
            const ResultDataBox = document.getElementById('ResultData');
            let resultText = ''
            SetAntibioticsObj.map((x) => {
                console.log(x);
                resultText += `
                <tr>
                    <td>${x.ccr}~</td>
                    <td>${x.dose}</td>
                    <td>${x.intervel}</td>
                </tr>       
                `;
            });
            ResultDataBox.innerHTML = resultText;
        };
    </script>
    <style>
        #whole {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            margin-top: 30px;
        }

        #resultBox {
            margin-top: 20px;
        }
    </style>
</body>

</html>