function asenacBuild($code) {
    $out = "";
    $lines = explode("\n", $code);
    $lines = array_map(fn($e) => "  " . $e, $lines);

    function space($count) {
        return str_repeat("  ", $count);
    }

    function toBlock($lines) {
        $stack = [];
        foreach ($lines as $line) {
            $indent = 0;
            while (isset($line[$indent]) && $line[$indent] == ' ') {
                $indent++;
            }
            $indent /= 2;
            $stack[] = [
                "line" => substr($line, $indent * 2),
                "indent" => $indent
            ];
        }

        $close = [];
        $out = "";
        $isParentAdded = false;
        $parentIndent = 0;
        $firstParentIndent = PHP_INT_MAX;

        foreach ($stack as $i => $lineData) {
            $row = $lineData["line"];
            $indent = $lineData["indent"];
            $prev = $stack[$i - 1] ?? null;
            $next = $stack[$i + 1] ?? null;

            if (trim($row) === "" || strpos(trim($row), "//") === 0) {
                $out .= $row . "\n";
                continue;
            }

            rsort($close);
            foreach ($close as $j => $closeIndent) {
                if ($closeIndent > $indent) {
                    $out .= space($closeIndent) . "}\n";
                    unset($close[$j]);
                }
                if ($firstParentIndent >= $indent) {
                    $isParentAdded = false;
                }
            }

            if (preg_match('/<[^\s=].*/', $row, $mat)) {
                $html = str_replace(["`", "{"], ["\\`", "\${"], $mat[0]);
                $prefix = str_replace($mat[0], "", $row);
                $row = space($indent) . "{\n";

                if ($indent > $parentIndent && $isParentAdded) {
                    $row .= space($indent) . "let PARENT = that; {\n";
                    $close[] = $indent;
                }

                if (preg_match('/^(let|const|var)\s/', trim($prefix))) {
                    $row .= space($indent + 1) . str_replace("=", "", trim($prefix)) . ";\n";
                    $prefix = trim(substr($prefix, strpos($prefix, " ")));
                }

                if (trim($prefix) === "return") {
                    $row .= space($indent + 1) . "$prefix Asenac(`$html`);\n";
                } elseif (substr(trim($prefix), -2) === "=>") {
                    $row .= space($indent + 1) . "$prefix Asenac(`$html`);\n";
                } else {
                    $row .= space($indent + 1) . "let that = " . ($isParentAdded ? "" : "PARENT = ") . "$prefix Asenac(`$html`);\n";
                }

                if (!$isParentAdded) {
                    $firstParentIndent = $indent;
                }

                if ($isParentAdded) {
                    $row .= space($indent + 1) . "PARENT.append(that);\n";
                }
                $isParentAdded = true;
                $parentIndent = $indent;
                $close[] = $indent;
                $out .= $row;
            } else {
                $out .= space($indent) . $row . "\n";
            }
        }

        rsort($close);
        foreach ($close as $closeIndent) {
            if ($closeIndent >= 0) {
                $out .= space($closeIndent) . "}\n";
            }
        }

        return $out;
    }

    return toBlock($lines);
}
