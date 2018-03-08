package pagination.models;

import java.math.BigInteger;

import com.google.gson.annotations.Expose;
import com.google.gson.annotations.SerializedName;

/**
 * Created by peo5032 on 3/7/18.
 */

public class SearchResultSection {

    @SerializedName("location")
    @Expose
    private String location;
    @SerializedName("section_id")
    @Expose
    private Integer sectionId;
    @SerializedName("offering")
    @Expose
    private String offering;
    @SerializedName("class_title")
    @Expose
    private String classTitle;
    @SerializedName("class_section_number")
    @Expose
    private Integer classSectionNumber;

    public String getLocation() {
        return location;
    }

    public void setLocation(String location) {
        this.location = location;
    }

    public Integer getSectionId() {
        return sectionId;
    }

    public void setSectionId(Integer sectionId) {
        this.sectionId = sectionId;
    }

    public String getOffering() {
        return offering;
    }

    public void setOffering(String offering) {
        this.offering = offering;
    }

    public String getClassTitle() {
        return classTitle;
    }

    public void setClassTitle(String classTitle) {
        this.classTitle = classTitle;
    }

    public Integer getClassSectionNumber() {
        return classSectionNumber;
    }

    public void setClassSectionNumber(Integer classSectionNumber) {
        this.classSectionNumber = classSectionNumber;
    }

    @Override
    public String toString(){
        return "title: " + getClassTitle() +
                " class section number: " + getClassSectionNumber()+
                " location: " + getLocation()+
                " offering: " + getOffering()+
                " section id: " + getSectionId() + "\n";
    }
}
