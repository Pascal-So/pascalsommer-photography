use svg::node::element::{
    SVG,
    Path as SVGPath,
    path::Data as SVGData
};

use crate::line::{Line, Pos};

pub fn to_svg<FilePath: AsRef<std::path::Path>>(out_path: FilePath, lines: &Vec<Line>, stroke_width: f32) {
    let canvas_side_length = 10.;

    let grid_size = canvas_side_length / (2. + stroke_width);
    let abs_stroke_width = stroke_width * grid_size;

    let padding = abs_stroke_width / 2.;

    let base_path = SVGPath::new()
        .set("fill", "none")
        .set("stroke", "#eeeeee")
        .set("stroke-width", abs_stroke_width);

    let mut document = SVG::new()
        .set("viewBox", (0., 0., canvas_side_length, canvas_side_length));

    for line in lines {
        if line.0.len() < 2 {
            continue;
        }

        let scaled = (line * grid_size + Pos::new(padding, padding)).0;
        let mut data = SVGData::new()
            .move_to((scaled[0].x, scaled[0].y));

        for p in scaled.into_iter().skip(1) {
            data = data.line_to((p.x, p.y));
        }

        let path = base_path
            .clone()
            .set("d", data);

        document = document.add(path);
    }

    svg::save(out_path, &document).unwrap();
}
