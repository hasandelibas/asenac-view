# Asenac 
A basic view engine

## test.js  |  Create View
```js
function test(){
  let parent=<div class="parent"></div>
    let child=<div class="item"></div>
  return parent;
}
```

## index.html  |  Call View 
```html
<script src="https://cdn.jsdelivr.net/gh/HasanDelibas/AsenacView@main/Asenac.js"></script>
<script>
  Asenac("path/to/test.js").then( ()=>{
    document.body.append( test() )
  })
</script>
```
