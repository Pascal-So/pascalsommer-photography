mod to_svg;
mod line;

use line::{Line, Pos};
use to_svg::to_svg;

fn flip_canvas(lines: &Vec<Line>) -> Vec<Line> {
    lines.iter()
        .map(|line| line * Pos::new(-1., 1.) + Pos::new(2., 0.))
        .collect()
}

fn main() {
    let arrow_right = Line(vec![
        Pos::new(0., 0.),
        Pos::new(1., 1.),
        Pos::new(0., 2.),
    ]);

    let stop_line = Line(vec![
        Pos::new(0., 0.1),
        Pos::new(0., 1.9),
    ]);

    let right_single_symbol = vec![
        &arrow_right + Pos::new(0.5, 0.),
    ];

    let stop_line_distance = 0.5;

    let end_symbol_width = 1. + stop_line_distance;
    let end_arrow_offset = (2. - end_symbol_width) / 2.;
    let end_line_offset = end_arrow_offset + 1. + stop_line_distance;

    let right_end_symbol = vec![
        &arrow_right + Pos::new(end_arrow_offset, 0.),
        &stop_line + Pos::new(end_line_offset, 0.),
    ];

    let left_single_symbol = flip_canvas(&right_single_symbol);
    let left_end_symbol = flip_canvas(&right_end_symbol);

    let stroke_width = 0.12;

    let out_dir = std::path::Path::new("../../resources/views/icons/");

    to_svg(out_dir.join("right.blade.php"), &right_single_symbol, stroke_width);
    to_svg(out_dir.join("right_end.blade.php"), &right_end_symbol, stroke_width);
    to_svg(out_dir.join("left.blade.php"), &left_single_symbol, stroke_width);
    to_svg(out_dir.join("left_end.blade.php"), &left_end_symbol, stroke_width);
    to_svg(out_dir.join("arrow_placeholder.blade.php"), &vec![], stroke_width);
}
