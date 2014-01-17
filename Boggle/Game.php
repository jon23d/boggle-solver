<?
    namespace Boggle;

    class Game {

        /** @var Tile[][] $rows     An array of arrays containing Tiles, aka, the board itself */
        private $rows = array();

        /** @var Tree $Tree     A tree structure containing all words in the Dictionary */
        private $Tree = null;

        /** @var string[] $word_list        A list of found words on the board */
        private $word_list = null;

        /** @var int $height            The height of the board */
        private $height = null;

        /** @var int $width             The width of the board */
        private $width = null;

        private $iterations = 0;

        /**
         * Create a new Game
         *
         * @throws \InvalidArgumentException
         *
         * @param Dictionary $Dictionary        The Dictionary to use
         * @param int $width        The number of letters horizontally on the board
         * @param int $height       The number of letters vertically on the board
         *
         * @return Game
         */
        function __construct(Dictionary $Dictionary, $width = 4, $height = 4) {
            // basic validation
            if (
                !is_int($width) || !is_int($height)
                || $width < 1 || $height < 1
            ) {
                throw new \InvalidArgumentException('Height and width must be integers greater than 1');
            }

            $this->initializeBoard($width, $height, true)
                ->initializeTree($Dictionary)
                ->height = $height;
            $this->width = $width;
            echo '<pre>';
                print_r($this->Tree);
            echo '</pre>';
        }


        /**
         * Output a simple table representing the board
         *
         * @return Game
         */
        public function outputBoardAsHtml() {
            echo '<table>';
            foreach ($this->rows as $Tiles) {
                echo '<tr>';
                /** @var Tile[] $Tiles */
                foreach ($Tiles as $Tile) {
                    echo '<td>' . $Tile->getCharacter() . '</td>';
                }
                echo '</tr>';
            }
            echo '</table>';

            return $this;
        }


        /**
         * Output a four-column table with the found words
         *
         * @return Game
         */
        public function outputWordListAsHtml() {
            // we really don't need to find the words more than once...
            if (is_null($this->word_list)) {

                // the getWords function will recursively search the board, but must be provided the first Tile
                foreach ($this->rows as $Tiles) {
                    /** @var Tile[] $Tiles */
                    foreach ($Tiles as $Tile) {
                        $this->getWords($Tile, $Tile->getCharacter());
                    }
                }
            }

            // separate out the word list into four columns
            $columns = array_chunk($this->word_list, count($this->word_list / 4));

            // and show a simple table
            echo '<table><tr>';
            foreach ($columns as $column) {
                echo '<td>' . implode('<br />', $column) . '</td>';
            }
            echo '</tr></table>';

            return $this;
        }


        /**
         * Initialize the board
         *
         * @param int $width
         * @param int $height
         * @param bool $use_test_data
         *
         * @return Game
         */
        private function initializeBoard($width, $height, $use_test_data = false) {
            $test_data = array('b','e','c','k');

            for ($row = 1; $row <= $height; $row++) {
                for ($column = 1; $column <= $width; $column++) {
                    if ($use_test_data === false) {
                        $this->rows[$row][$column] = new Tile(chr(97 + mt_rand(0, 25)), $row, $column);
                    } else {
                        $letter = array_shift($test_data);
                        $this->rows[$row][$column] = new Tile($letter, $row, $column);
                        $test_data[] = $letter;
                    }

                }
            }

            return $this;
        }


        /**
         * Initialize the Tree
         *
         * @param Dictionary $Dictionary
         *
         * @param Dictionary $Dictionary
         *
         * @return Game
         */
        private function initializeTree(Dictionary $Dictionary) {
            $Tree = new Tree();

            foreach ($Dictionary->getWords($this->getLetters()) as $word) {
                $Tree->addWord($word);
            }

            $this->Tree = $Tree;
            return $this;
        }


        /**
         * Get a string representing all of the letters on this board
         *
         * @return string
         */
        private function getLetters() {
            $return_val = '';

            foreach ($this->rows as $Tiles) {
                /** @var Tile[] $Tiles */
                foreach ($Tiles as $Tile) {
                    /** @var Tile $Tile */
                    $return_val .= $Tile->getCharacter();
                }
            }

            return $return_val;
        }


        /**
         * Get an array of words for the provided Tile with the provided prefix found in the Tree
         *
         * @param Tile $Tile
         * @param string $prefix
         *
         * @return string[]
         */
        private function getWords(Tile $Tile, $prefix) {
            if (++$this->iterations == 1000) {
                print_r($this->word_list);
                die;
            }

            // If this Tile ends a word, add it to the list, but continue processing any potential neighbors
            if ($this->Tree->doesWordExist($prefix)) {
                echo $prefix . '<br />';
                $this->word_list[] = $prefix;
            }

            // mark this Tile as used to prevent an infinite loop
            $Tile->setIsUsed(true);

            // loop through the neighboring Tiles, checking the word list with the non-used ones appended to the prefix
            foreach ($this->getNeighbors($Tile) as $Neighbor) {
                if (!$Neighbor->getIsUsed()) {
                    $this->getWords($Neighbor, $prefix . $Neighbor->getCharacter());
                }
            }

            // We are done with this iteration, allow this Tile to be used in future combinations
            $Tile->setIsUsed(false);
        }


        /**
         * Get the all neighboring Tiles to the provided Tile
         *
         * @param Tile $Tile
         *
         * @return Tile[]
         */
        private function getNeighbors(Tile $Tile) {
            $Tiles = array();

            $row = $Tile->getRow();
            $column = $Tile->getColumn();

            $min_row = ($row - 1 < 0) ? 0 : $row - 1;
            $min_column = ($column - 1 < 0) ? 0 : $column - 1;
            $max_row = ($row + 1 > $this->height) ? $this->height : $row + 1;
            $max_column = ($column + 1 > $this->width) ? $this->width : $column + 1;

            for ($r = $min_row; $r <= $max_row; $r++) {
                for ($c = $min_column; $c <= $max_column; $c++) {
                    if ($r == $row && $c == $column) continue;

                    if (isset($this->rows[$r][$c])) {
                        $Tiles[] = $this->rows[$r][$c];
                    }
                }
            }

            return $Tiles;
        }

    }