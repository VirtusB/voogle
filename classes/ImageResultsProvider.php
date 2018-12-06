<?php

class ImageResultsProvider {
    private $_conn;

    public function __construct(PDO $conn)
    {
        $this->_conn = $conn;
    }

    public function getNumResults($term) {
        $stmt = 'SELECT COUNT(*) as total 
                FROM images 
                WHERE (title LIKE :term 
                OR alt LIKE :term2)
                AND (broken = 0 OR broken IS NULL)';

        $query = $this->_conn->prepare($stmt);

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);


        $query->execute();

        return $query->fetch(PDO::FETCH_ASSOC)['total'];
    }

    public function getResultsHTML(int $page_num, int $page_size, string $term) {
        $fromLimit = ($page_num - 1) * $page_size;
        $page_size = intval($page_size);

        $stmt = 'SELECT * 
                FROM images 
                WHERE (title LIKE :term OR alt LIKE :term2)
                AND (broken = 0 OR broken IS NULL)
                ORDER BY clicks DESC
                OFFSET :from_limit ROWS
                FETCH NEXT :page_size ROWS ONLY';

        $query = $this->_conn->prepare($stmt, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

        $searchTerm = "%$term%";
        $query->bindParam(':term', $searchTerm);
        $query->bindParam(':term2', $searchTerm);
        $query->bindParam(':from_limit', $fromLimit,PDO::PARAM_INT);
        $query->bindParam(':page_size', $page_size, PDO::PARAM_INT);


        $query->execute();

        $resultsHTML = "<div class='image-results'>";
        $countRun = 0;

        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $countRun++;

            $id = $row['id'];
            $imageUrl = $row['image_url'];
            $siteUrl = $row['site_url'];
            $title = $row['title'];
            $alt = $row['alt'];
            
            if ($title) {
                $displayText = $title;
            } else if ($alt) {
                $displayText = $alt;
            } else {
                $displayText = $imageUrl;
            }

            $resultsHTML .= "<div data-image-id='$id' class='grid-item lightgallery'>
                                <a data-sub-html='#caption-$id' href='$imageUrl'>
                                <script>
                                    document.addEventListener('DOMContentLoaded', function() {
                                        loadImage(\"$imageUrl\", \"$id\");
                                    });
                                </script>
                                <span class='details'>$displayText</span>
                                
                                <div id='caption-$id' style='display:none'>
                                    <a style='margin-right: 5px;' target='_blank' href='$imageUrl'>View image</a>
                                    <a style='margin-left: 5px;' target='_blank' href='$siteUrl'>Visit page</a>
                                </div>
                                </a>
                            </div>";
        }

        $resultsHTML .= '</div>';

        return $resultsHTML;
    }
}