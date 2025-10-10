use nalgebra as na;
use impl_ops::*;
use std::ops;

pub type Pos = na::Vector2<f32>;

#[derive(Debug, Clone, PartialEq)]
pub struct Line(pub Vec<Pos>);

impl_op_ex!(+ |a: &Line, b: &Pos| -> Line { Line (
    a.0
        .iter()
        .map(|p| p + b)
        .collect()
)});

impl_op_ex!(* |a: &Line, b: &Pos| -> Line { Line (
    a.0
        .iter()
        .map(|p| Pos::new(p.x * b.x, p.y * b.y))
        .collect()
)});

impl_op_ex!(* |a: &Line, b: &f32| -> Line { Line (
    a.0
        .iter()
        .map(|p| p * *b)
        .collect()
)});
