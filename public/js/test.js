// Dans la ram, les éléments sont stockés à deux endroits 
// le Stack (pile) et le heap (tas)

let a = {
  x: 1,
  y: 2
};

let b = a;

console.log(b);

a.x = 10;

console.log(b);