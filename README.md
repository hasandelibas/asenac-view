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

## test.css 
```css
html,body{
  margin:0;
  padding:0;
}
```

## index.html  |  Call View 
```html
<script src="https://cdn.jsdelivr.net/gh/HasanDelibas/asenac-view@v1.0.0/asenac.js"></script>
<script>
  Asenac("path/to/test.js","path/to/test.css").then( ()=>{
    document.body.append( test() )
  })
</script>
```
