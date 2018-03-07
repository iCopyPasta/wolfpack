package pagination.models;

import java.math.BigInteger;

/**
 * Created by peo5032 on 3/7/18.
 */

public class SearchResultSection {

    private BigInteger section_id;

    private String class_title;

    private int class_section_number;
    private String location;
    private String offering;
    public BigInteger getSection_id() {
        return section_id;
    }

    public String getClass_title() { return class_title; }

    public void setClass_title(String class_title) { this.class_title = class_title; }

    public void setSection_id(BigInteger section_id) {
        this.section_id = section_id;
    }

    public int getClass_section_number() {
        return class_section_number;
    }

    public void setClass_section_number(int class_section_number) {
        this.class_section_number = class_section_number;
    }

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public String getOffering() {
        return offering;
    }

    public void setOffering(String offering) {
        this.offering = offering;
    }
}
