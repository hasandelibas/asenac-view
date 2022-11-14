# Asenac 
A basic view engine

## test.js
```js
function test(){
  let parent=<div class="parent"></div>
    let child=<div class="item"></div>
  return parent;
}
```

## Call View 
```js
Asenac("path/to/test.js").then( ()=>{
  document.body.append( test() )
})
```
