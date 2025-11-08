<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="searchbarcss">
</head>
<style>
    *{
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: 'poppins', sans-serif;
    font-size: 18px;
}
body{
    margin: 4vh;
}
.container{
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.sidebar{
width: 25%;
border: apx solid #eee;
border-radius: 3px;
padding: 15px;
height: 92vh;
box-shadow: 0px 0px 3px gray;
display: flex;
flex-direction: column;
justify-content: space-between;
}
.data{
    width: 73%;
    border-radius: 3px;
    height: 92vh;
    overflow-y: auto;
}
.fa-circle{
    color: blueviolet;
}
.searchbar{
    width: 100%;
    background-color: #eee;
    border-radius: 3px;
    padding: 9px;
    display: flex;
    align-items: center;
    justify-content: space-around; 
}
input{
    border: none;
    outline: none;
    background: none;
}
.fa-brands{
    font-size: 25px;
    margin: 0 10px;
    color: #333;
    cursor: pointer;
}
.top{
    height: 60px;
    border-radius: 3px;
    background-color: #eee;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 15px;
}
.header{
    height: 200px;
    border-radius: 3px;
    background-color: #333;
    margin: 3vh 0;
    display: flex;
    align-items: center;
    justify-content: center;
}
.header p{
    font-size: 40px;
    font-weight: bold;
    color: white;
}

</style>
<body>
<div class="container">
    <div class="sidebar">
    <div class="sidehead">
        <div class="dots">
            <i class="fa-solid fa-circle"></i>
            <i class="fa-solid fa-circle" style="color: #333;"></i>
            <i class="fa-solid fa-circle"></i>
        </div>
        <hr style="margin: 15px 0; border: 1px solid #eee;">
    </div>
    <div class="sidebody" style="height: 69vh;">
        <div class="searchbar">
            <input placeholder="Search..." id="searchbar" name="searchbar"
            type="text">
        </div>
    </div>
    <div class="sidefoot">
        <hr style="margin: 15px 0; border: 1px solid #eee;">
    </div>
    </div>
    <div class="data">
        <div class="top">
            <p>+923185700008</p>
            <p>abc@gmail.com</p>
        </div>
        <div class="header">
            <p>Buy everything online</p>
        </div>
        <div class="body">
            <div id="root"></div>
        </div>
    </div>
</div>
    <script src="searchbar.js"></script>
</body>
<script>
    const product=[
    {
        id: 0,
        image: '',
        title: '',
        price
    },
]
const categories = [...new Set(product.map((item)=> {return item}))]

document.getElementById('searchbar').addEventListener('keyup', (e)=>{
   const searchData = e.target.value.toLowerCase();
   const filterData = categories.filter((item)=>{
    return(
        item.title.toLocaleLowerCase().includes(searchData)
    )
   })
   displayItem(filterData)
});

const displayItem = (items)=> {
    document.getElementById('root').innerHTML=items.map((item)=>{
        var{image, title, price,} = item;
        return(
            `<div class='box'>
              <div class='img-box'>
                <img class='images' src=${image}></img>
                </div>
                <div class='bottom'>
                    <p>${title}></p>
                    <h2>$ ${price}.00</h2>
                    </div>
                    </div>`
        )
    }).join('')
};
displayItem(categories);

</script>
</html>