<?
    namespace Boggle;

    class Game {

        /** @var Tile[][] $rows     An array of arrays containing Tiles, aka, the board itself */
        private $rows = array();

        /** @var Tree $Tree     A tree structure containing all words in the Dictionary */
        private $Tree = null;

        /** @var string[] $word_list        A list of found words on the board */
        private $word_list = null;

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

            $this->initializeBoard($width, $height)
                ->initializeTree($Dictionary);
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
                    /** @var Tile $Tiles */
                    foreach ($Tiles as $Tile) {
                        $this->word_list = array_merge($this->word_list, $this->getWords($Tile));
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
         *
         * @return Game
         */
        private function initializeBoard($width, $height) {
            for ($row = 1; $row <= $height; $row++) {
                for ($column = 1; $column <= $width; $column++) {
                    $this->rows[$row][$column] = new Tile(chr(97 + mt_rand(0, 25)), $row, $column);
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
            $return_val = array();

        }


        /**
         * Get the all neighboring Tiles to the provided Tile
         *
         * @param Tile $Tile
         *
         * @return Tile[]
         */
        private function getNeighbors(Tile $Tile) {

        }

    }